<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210606174924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant ADD id_provider INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11C21F9F09 FOREIGN KEY (id_provider) REFERENCES provider (id)');
        $this->addSql('CREATE INDEX IDX_D79F6B11C21F9F09 ON participant (id_provider)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11C21F9F09');
        $this->addSql('DROP INDEX IDX_D79F6B11C21F9F09 ON participant');
        $this->addSql('ALTER TABLE participant DROP id_provider');
    }
}
