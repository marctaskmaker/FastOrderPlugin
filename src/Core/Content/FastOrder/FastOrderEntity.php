<?php

declare(strict_types=1);

namespace FastOrderPlugin\Core\Content\FastOrder;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class FastOrderEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $product;

    protected int $quantity;

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(?string $product): void
    {
        $this->product = $product;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
