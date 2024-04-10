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
            'fastOrderPage' => 'active',
        ]);
    }

    #[Route(
        path: '/fast-order/articles/{id}',
        name: 'frontend.fast-order.articles',
        methods: ['GET'],
        defaults: ['XmlHttpRequest' => 'true']
    )]
    public function getArticles(Request $request, SalesChannelContext $salesContext, Context $context): Response
    {
        $articles = [];

        $id = $request->attributes->getAlnum('id');

        $productRepository = new ProductRepository($this->container->get('product.repository'));

        $products = $productRepository->searchAvailableProducts($context, $id);

        foreach ($products as $product) {
            $price = $product->getPrice()->getCurrencyPrice($salesContext->getCurrency()->getId());
            $articles[] = [
                'productNumber' => $product->getProductNumber(),
                'stock' => $product->getStock(),
                'price' => $price->getGross(),
            ];
        }

        return $this->renderStorefront('@FastOrderPlugin/storefront/page/component/fast-order-articles.html.twig', [
            'articles' => $articles,
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
            if (! empty($product['article']) && ! empty($product['quantity'])) {

                $session = $salesContext->getToken();

                $currentDateTime = $salesContext->getCurrentCustomerGroup()
                    ->getCreatedAt()
                    ->format(Defaults::STORAGE_DATE_TIME_FORMAT);

                $fastOrderItem = [
                    'article' => $product['article'],
                    'quantity' => intval($product['quantity']),
                    'session' => $session,
                    'created_at' => $currentDateTime,
                    'updated_at' => $currentDateTime,
                ];

                $fastOrderRepository->create([$fastOrderItem], $context);

                $lineItem[] = $fastOrderProcessor->createFastOrderLine(
                    [
                        'referencedId' => $product['article'],
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
