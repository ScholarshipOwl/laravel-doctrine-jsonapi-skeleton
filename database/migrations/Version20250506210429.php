<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506210429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cache (`key` VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, expiration INT NOT NULL, PRIMARY KEY(`key`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE cache_locks (`key` VARCHAR(255) NOT NULL, owner VARCHAR(255) NOT NULL, expiration INT NOT NULL, PRIMARY KEY(`key`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE failed_jobs (id BIGINT AUTO_INCREMENT NOT NULL, uuid VARCHAR(255) NOT NULL, connection LONGTEXT NOT NULL, queue LONGTEXT NOT NULL, payload LONGTEXT NOT NULL, exception LONGTEXT NOT NULL, failed_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', UNIQUE INDEX UNIQ_E338C41FD17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE job_batches (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, total_jobs INT NOT NULL, pending_jobs INT NOT NULL, failed_jobs INT NOT NULL, failed_job_ids LONGTEXT NOT NULL, options LONGTEXT DEFAULT NULL, cancelled_at INT DEFAULT NULL, created_at INT NOT NULL, finished_at INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE jobs (id BIGINT AUTO_INCREMENT NOT NULL, queue VARCHAR(255) NOT NULL, payload LONGTEXT NOT NULL, attempts SMALLINT UNSIGNED NOT NULL, reserved_at INT UNSIGNED DEFAULT NULL, available_at INT UNSIGNED NOT NULL, created_at INT UNSIGNED NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE password_reset_tokens (email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(email)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, permissions JSON NOT NULL, UNIQUE INDEX UNIQ_B63E2EC75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sessions (id VARCHAR(255) NOT NULL, user_id BIGINT DEFAULT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent LONGTEXT DEFAULT NULL, payload LONGTEXT NOT NULL, last_activity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_verified_at DATETIME DEFAULT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, permissions JSON NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role_user (user_id BIGINT NOT NULL, role_id INT NOT NULL, INDEX IDX_332CA4DDA76ED395 (user_id), INDEX IDX_332CA4DDD60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC
        SQL);
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
