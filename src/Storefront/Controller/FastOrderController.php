<?php

declare(strict_types=1);

namespace FastOrderPlugin\Storefront\Controller;

use FastOrderPlugin\Service\ProductService;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItemFactoryRegistry;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class FastOrderController extends StorefrontController
{
    private LineItemFactoryRegistry $factory;

    private CartService $cartService;

    private ProductService $productService;

    public function __construct(LineItemFactoryRegistry $factory, CartService $cartService, ProductService $productService)
    {
        $this->factory = $factory;
        $this->cartService = $cartService;
        $this->productService = $productService;
    }

    #[Route(
        path: '/fast-order',
        name: 'frontend.fast-order.index',
        methods: ['GET']
    )]
    public function showFastOrder(Request $request, SalesChannelContext $salesContext): Response
    {
        return $this->renderStorefront('@FastOrderPlugin/storefront/page/fast-order.html.twig', [
            'currency' => $salesContext->getCurrency()->getSymbol(),
            'fastOrderPage' => 'index',
            'fastOrderContent' => '@FastOrderPlugin/storefront/page/component/fast-order-form.html.twig',
        ]);
    }

    #[Route(
        path: '/fast-order/upload',
        name: 'frontend.fast-order.upload',
        methods: ['GET']
    )]
    public function showFastOrderUpload(Request $request, SalesChannelContext $salesContext): Response
    {
        return $this->renderStorefront('@FastOrderPlugin/storefront/page/fast-order.html.twig', [
            'currency' => $salesContext->getCurrency()->getSymbol(),
            'fastOrderPage' => 'upload',
            'fastOrderContent' => '@FastOrderPlugin/storefront/page/component/fast-order-upload.html.twig',
        ]);
    }

    #[Route(
        path: '/fast-order/upload-post',
        name: 'frontend.fast-order.upload-post',
        methods: ['POST']
    )]
    public function uploadPost(Request $request, SalesChannelContext $salesContext, Context $context, Cart $cart): Response
    {
        return $this->redirectToRoute('frontend.checkout.cart.page');
    }

    #[Route(
        path: '/fast-order/articles/{name}',
        name: 'frontend.fast-order.articles',
        methods: ['GET'],
        defaults: ['XmlHttpRequest' => 'true']
    )]
    public function getArticles(Request $request, SalesChannelContext $salesContext, Context $context): Response
    {

        $search = $request->attributes->getAlnum('name');

        $products = $this->productService->searchAvailableProducts($context, $search);

        $articles = [];
        foreach ($products as $product) {
            $price = $product->getPrice()->getCurrencyPrice($salesContext->getCurrency()->getId());

            $name = $product->getName();
            $translated = $product->getTranslated();

            if ($translated) {
                $name = $translated['name'];
            }

            $articles[] = [
                'name' => $name,
                'number' => $product->getProductNumber(),
                'stock' => $product->getStock(),
                'options' => $product->getOptions(),
                'media' => $product->getCover()->getMedia(),
                'price' => $price->getGross(),
            ];
        }

        return $this->renderStorefront('@FastOrderPlugin/storefront/page/component/fast-order-articles.html.twig', [
            'articles' => $articles,
            'currency' => $salesContext->getCurrency()->getSymbol(),
        ]);
    }

    #[Route(
        path: '/fast-order/add-to-cart',
        name: 'frontend.fast-order.add-to-cart',
        methods: ['POST']
    )]
    public function addToCart(Request $request, SalesChannelContext $salesContext, Context $context, Cart $cart): Response
    {
        $products = $request->get('products');

        $productNumbers = [];
        $productQuantities = [];
        $fastOrderItems = [];
        foreach ($products as $product) {

            if ($product['number'] && $product['quantity']) {
                $productNumber = $product['number'];
                $productQuantity = intval($product['quantity']);

                $productNumbers[] = $productNumber;

                if (isset($productQuantities[$productNumber])) {
                    $productQuantities[$productNumber] += $productQuantity;
                } else {
                    $productQuantities[$productNumber] = $productQuantity;
                }

                $session = $salesContext->getToken();

                $currentDateTime = $salesContext->getCurrentCustomerGroup()
                    ->getCreatedAt()
                    ->format(Defaults::STORAGE_DATE_TIME_FORMAT);

                $fastOrderItems[] = [
                    'product' => $productNumber,
                    'quantity' => $productQuantity,
                    'session' => $session,
                    'created_at' => $currentDateTime,
                ];
            }
        }

        $productNumbers = array_unique($productNumbers);

        $activeProductNumbers = $this->addProductsToCart($productNumbers, $productQuantities, $context, $salesContext, $cart);

        $fastOrderRepository = $this->container->get('fast_order.repository');

        foreach ($fastOrderItems as $fastOrderItem) {
            if (in_array($fastOrderItem['product'], $activeProductNumbers)) {
                $fastOrderRepository->create([$fastOrderItem], $context);
            }
        }

        return $this->redirectToRoute('frontend.checkout.cart.page');
    }

    private function addProductsToCart(array $productNumbers, array $productQuantities, Context $context, SalesChannelContext $salesContext, Cart $cart): array
    {
        $products = $this->productService->getProductsByProductNumbers($context, $productNumbers);

        $activeProductNumbers = [];
        foreach ($products as $product) {

            $activeProductNumbers[] = $product->getProductNumber();

            $lineItem = $this->factory->create([
                'type' => LineItem::PRODUCT_LINE_ITEM_TYPE,
                'referencedId' => $product->getId(),
                'quantity' => $productQuantities[$product->getProductNumber()],
                'payload' => [],
            ], $salesContext);

            $this->cartService->add($cart, $lineItem, $salesContext);
        }

        return $activeProductNumbers;
    }
}
