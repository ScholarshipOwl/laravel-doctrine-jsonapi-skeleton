<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418210636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE password_reset_tokens (
                email VARCHAR(255) NOT NULL,
                token VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT NULL,
                PRIMARY KEY(email)
            )
        ');
        $this->addSql('
            CREATE TABLE sessions (
                id VARCHAR(255) NOT NULL,
                user_id BIGINT DEFAULT NULL,
                ip_address VARCHAR(45) DEFAULT NULL,
                user_agent CLOB DEFAULT NULL,
                payload CLOB NOT NULL,
                last_activity INTEGER NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE password_reset_tokens
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sessions
        SQL);
    }
}
