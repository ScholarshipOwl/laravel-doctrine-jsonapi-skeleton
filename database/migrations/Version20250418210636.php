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
        $this->addSql('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                email_verified_at DATETIME DEFAULT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(255) DEFAULT NULL
            )
        ');
        $this->addSql('
            CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)
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
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
    }
}
