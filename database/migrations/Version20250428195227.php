<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428195227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE roles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, permissions CLOB NOT NULL --(DC2Type:json)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B63E2EC75E237E06 ON roles (name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, permissions CLOB NOT NULL --(DC2Type:json)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role_user (user_id BIGINT NOT NULL, role_id INTEGER NOT NULL, PRIMARY KEY(user_id, role_id), CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_332CA4DDA76ED395 ON role_user (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_332CA4DDD60322AC ON role_user (role_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE roles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role_user
        SQL);
    }
}
