<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115180923 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement ADD idempotency_key VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE advertisement ADD status INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_idempotencyKey ON advertisement (idempotency_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_idempotencyKey');
        $this->addSql('ALTER TABLE advertisement DROP idempotency_key');
        $this->addSql('ALTER TABLE advertisement DROP status');
    }
}
