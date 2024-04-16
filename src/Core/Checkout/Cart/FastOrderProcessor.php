<?php

declare(strict_types=1);

namespace FastOrderPlugin\Core\Checkout\Cart;

use FastOrderPlugin\Service\FastOrderHandler;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class FastOrderProcessor implements CartProcessorInterface
{
    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
    {
        $lineItems = $original->getLineItems()->filterFlatByType(FastOrderHandler::TYPE);

        foreach ($lineItems as $lineItem) {
            $toCalculate->add($lineItem);
        }
    }
}
