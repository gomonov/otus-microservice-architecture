<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117162920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus_transaction (id SERIAL NOT NULL, bonus_id INT NOT NULL, value INT NOT NULL, idempotency_key VARCHAR(36) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_487D3D7D69545666 ON bonus_transaction (bonus_id)');
        $this->addSql('CREATE INDEX IDX_idempotencyKey ON bonus_transaction (idempotency_key)');
        $this->addSql('ALTER TABLE bonus_transaction ADD CONSTRAINT FK_487D3D7D69545666 FOREIGN KEY (bonus_id) REFERENCES bonus (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus_transaction DROP CONSTRAINT FK_487D3D7D69545666');
        $this->addSql('DROP TABLE bonus_transaction');
    }
}
