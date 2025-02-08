<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118190705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account_transaction (id SERIAL NOT NULL, account_id INT NOT NULL, value INT NOT NULL, idempotency_key VARCHAR(36) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A370F9D29B6B5FBA ON account_transaction (account_id)');
        $this->addSql('CREATE INDEX IDX_idempotencyKey ON account_transaction (idempotency_key)');
        $this->addSql('ALTER TABLE account_transaction ADD CONSTRAINT FK_A370F9D29B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_transaction DROP CONSTRAINT FK_A370F9D29B6B5FBA');
        $this->addSql('DROP TABLE account_transaction');
    }
}
