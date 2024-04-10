<?php

declare(strict_types=1);

namespace FastOrderPlugin\Core\Content\FastOrder;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class FastOrderDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'fast_order';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return FastOrderEntity::class;
    }

    public function getCollectionClass(): string
    {
        return FastOrderCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
                (new StringField('article', 'article'))->addFlags(new Required()),
                (new IntField('quantity', 'quantity'))->addFlags(new Required()),
                (new StringField('session', 'session'))->addFlags(new Required()),
                (new DateTimeField('created_at', 'created_at'))->addFlags(new Required()),
                (new DateTimeField('updated_at', 'updated_at'))->addFlags(new Required()),
            ]
        );
    }
}
