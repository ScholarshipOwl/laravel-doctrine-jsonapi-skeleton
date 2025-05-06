<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506222458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE personal_access_tokens (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, name VARCHAR(255) NOT NULL, token VARCHAR(64) NOT NULL, abilities JSON DEFAULT NULL, last_used_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', expires_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E63C21665F37A13B (token), INDEX IDX_E63C2166A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE personal_access_tokens ADD CONSTRAINT FK_E63C2166A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE personal_access_tokens DROP FOREIGN KEY FK_E63C2166A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE personal_access_tokens
        SQL);
    }
}
