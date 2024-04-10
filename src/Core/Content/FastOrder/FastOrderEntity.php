<?php

declare(strict_types=1);

namespace FastOrderPlugin\Core\Content\FastOrder;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class FastOrderEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $article;

    protected int $quantity;

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(?string $article): void
    {
        $this->article = $article;
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
