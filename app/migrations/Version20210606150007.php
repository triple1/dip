<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210606150007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD id_provider INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C21F9F09 FOREIGN KEY (id_provider) REFERENCES provider (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C21F9F09 ON user (id_provider)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C21F9F09');
        $this->addSql('DROP INDEX IDX_8D93D649C21F9F09 ON user');
        $this->addSql('ALTER TABLE user DROP id_provider');
    }
}
