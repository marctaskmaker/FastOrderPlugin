<?php

declare(strict_types=1);

namespace FastOrderPlugin\Storefront\Controller;

use FastOrderPlugin\Core\Checkout\Cart\FastOrderProcessor;
use FastOrderPlugin\Service\ProductRepository;
use Shopware\Core\Checkout\Cart\Cart;
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
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
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
        path: '/fast-order/articles/{name}',
        name: 'frontend.fast-order.articles',
        methods: ['GET'],
        defaults: ['XmlHttpRequest' => 'true']
    )]
    public function getArticles(Request $request, SalesChannelContext $salesContext, Context $context): Response
    {
        $articles = [];

        $name = $request->attributes->getAlnum('name');

        $productRepository = new ProductRepository($this->container->get('product.repository'));

        $products = $productRepository->searchAvailableProducts($context, $name);

        foreach ($products as $product) {
            $price = $product->getPrice()->getCurrencyPrice($salesContext->getCurrency()->getId());

            $articles[] = [
                'name' => $product->getName(),
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
        $fastOrderProcessor = new FastOrderProcessor();
        $fastOrderRepository = $this->container->get('fast_order.repository');

        $products = $request->get('products');

        $lineItem = [];

        foreach ($products as $product) {
            if (! empty($product['number']) && ! empty($product['quantity'])) {

                $session = $salesContext->getToken();

                $currentDateTime = $salesContext->getCurrentCustomerGroup()
                    ->getCreatedAt()
                    ->format(Defaults::STORAGE_DATE_TIME_FORMAT);

                $fastOrderItem = [
                    'product' => $product['number'],
                    'quantity' => intval($product['quantity']),
                    'session' => $session,
                    'created_at' => $currentDateTime,
                    'updated_at' => $currentDateTime,
                ];

                $fastOrderRepository->create([$fastOrderItem], $context);

                $lineItem[] = $fastOrderProcessor->createFastOrderLine(
                    [
                        'referencedId' => $product['number'],
                        'quantity' => intval($product['quantity']),
                    ], $salesContext,
                );

            }
        }

        if (! empty($lineItem)) {
            $this->cartService->add($cart, $lineItem, $salesContext);
        }

        return $this->redirectToRoute('frontend.checkout.cart.page');
    }
}
