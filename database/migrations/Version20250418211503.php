<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418211503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            <<<SQL
            CREATE TABLE failed_jobs (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                uuid VARCHAR(255) NOT NULL,
                connection CLOB NOT NULL,
                queue CLOB NOT NULL,
                payload CLOB NOT NULL,
                exception CLOB NOT NULL,
                failed_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            )
            SQL
        );
        $this->addSql(
            <<<SQL
            CREATE UNIQUE INDEX UNIQ_E338C41FD17F50A6 ON failed_jobs (uuid)
            SQL
        );
        $this->addSql(
            <<<SQL
            CREATE TABLE job_batches (
                id VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                total_jobs INTEGER NOT NULL,
                pending_jobs INTEGER NOT NULL,
                failed_jobs INTEGER NOT NULL,
                failed_job_ids CLOB NOT NULL,
                options CLOB DEFAULT NULL,
                cancelled_at INTEGER DEFAULT NULL,
                created_at INTEGER NOT NULL,
                finished_at INTEGER DEFAULT NULL,
                PRIMARY KEY(id)
            )
            SQL
        );
        $this->addSql(
            <<<SQL
            CREATE TABLE jobs (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                queue VARCHAR(255) NOT NULL,
                payload CLOB NOT NULL,
                attempts SMALLINT UNSIGNED NOT NULL,
                reserved_at INTEGER UNSIGNED DEFAULT NULL,
                available_at INTEGER UNSIGNED NOT NULL,
                created_at INTEGER UNSIGNED NOT NULL
            )
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE failed_jobs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE job_batches
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE jobs
        SQL);
    }
}
