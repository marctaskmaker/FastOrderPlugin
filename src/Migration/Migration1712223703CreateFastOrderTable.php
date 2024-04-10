<?php

declare(strict_types=1);

namespace FastOrderPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1712223703CreateFastOrderTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1712223703;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<'SQL'
        CREATE TABLE IF NOT EXISTS `fast_order` (
            `id` BINARY(16) NOT NULL,
            `article` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `quantity` INT NOT NULL,
            `session` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NOT NULL,
            PRIMARY KEY (`id`)
        )
            ENGINE = InnoDB
            DEFAULT CHARSET = utf8mb4
            COLLATE = utf8mb4_unicode_ci;
        SQL;

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
        $connection->executeStatement('DROP TABLE IF EXISTS `fast_order`');
    }
}
