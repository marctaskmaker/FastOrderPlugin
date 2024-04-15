<?php

declare(strict_types=1);

namespace FastOrderPlugin\Service;

use Shopware\Core\Content\Product\ProductCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;

class ProductRepository
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
}
