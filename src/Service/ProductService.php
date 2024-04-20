<?php

declare(strict_types=1);

namespace FastOrderPlugin\Service;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

class ProductService
{
    private EntityRepository $productRepository;

    public function __construct(EntityRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function searchAvailableProducts(Context $context, string $search): ProductCollection
    {
        $criteria = (new Criteria())
            ->addFilter(new ContainsFilter('name', $search))
            ->addFilter(new EqualsFilter('active', true))
            ->addFilter(new RangeFilter('stock', [RangeFilter::GT => 0]))
            ->addAssociation('cover.media')
            ->addAssociation('media.media')
            ->addAssociation('properties')
            ->addAssociation('description')
            ->addAssociation('options')
            ->setLimit(5);

        return $this->productRepository->search($criteria, $context)->getEntities();
    }

    public function getProductsByProductNumbers(Context $context, array $productNumbers): ProductCollection
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsAnyFilter('productNumber', $productNumbers))
            ->addFilter(new EqualsFilter('active', true));

        return $this->productRepository->search($criteria, $context)->getEntities();
    }
}
