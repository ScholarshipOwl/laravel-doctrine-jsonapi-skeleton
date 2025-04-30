<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250502122256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cache ("key" VARCHAR(255) NOT NULL, value CLOB NOT NULL, expiration INTEGER NOT NULL, PRIMARY KEY("key"))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cache_locks ("key" VARCHAR(255) NOT NULL, owner VARCHAR(255) NOT NULL, expiration INTEGER NOT NULL, PRIMARY KEY("key"))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE failed_jobs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, connection CLOB NOT NULL, queue CLOB NOT NULL, payload CLOB NOT NULL, exception CLOB NOT NULL, failed_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_E338C41FD17F50A6 ON failed_jobs (uuid)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE job_batches (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, total_jobs INTEGER NOT NULL, pending_jobs INTEGER NOT NULL, failed_jobs INTEGER NOT NULL, failed_job_ids CLOB NOT NULL, options CLOB DEFAULT NULL, cancelled_at INTEGER DEFAULT NULL, created_at INTEGER NOT NULL, finished_at INTEGER DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jobs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, queue VARCHAR(255) NOT NULL, payload CLOB NOT NULL, attempts SMALLINT UNSIGNED NOT NULL, reserved_at INTEGER UNSIGNED DEFAULT NULL, available_at INTEGER UNSIGNED NOT NULL, created_at INTEGER UNSIGNED NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE password_reset_tokens (email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(email))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE roles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, permissions CLOB NOT NULL --(DC2Type:json)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_B63E2EC75E237E06 ON roles (name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sessions (id VARCHAR(255) NOT NULL, user_id BIGINT DEFAULT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent CLOB DEFAULT NULL, payload CLOB NOT NULL, last_activity INTEGER NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, permissions CLOB NOT NULL --(DC2Type:json)
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
            DROP TABLE cache
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cache_locks
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE failed_jobs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE job_batches
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jobs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE password_reset_tokens
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE roles
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sessions
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role_user
        SQL);
    }
}
