<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212141729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advertisement ADD moder_key VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE advertisement ADD moder_fail_reason VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_moderKey ON advertisement (moder_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_moderKey');
        $this->addSql('ALTER TABLE advertisement DROP moder_key');
        $this->addSql('ALTER TABLE advertisement DROP moder_fail_reason');
    }
}
