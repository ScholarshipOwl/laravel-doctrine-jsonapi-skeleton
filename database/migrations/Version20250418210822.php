<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418210822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            <<< 'SQL'
            CREATE TABLE cache (
                "key" VARCHAR(255) NOT NULL,
                value CLOB NOT NULL,
                expiration INTEGER NOT NULL,
                PRIMARY KEY("key")
            )
            SQL
        );
        $this->addSql(
            <<< 'SQL'
            CREATE TABLE cache_locks (
                "key" VARCHAR(255) NOT NULL,
                owner VARCHAR(255) NOT NULL,
                expiration INTEGER NOT NULL,
                PRIMARY KEY("key")
            )
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE cache
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cache_locks
        SQL);
    }
}
