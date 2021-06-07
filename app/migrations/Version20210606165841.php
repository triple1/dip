<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210606165841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE referrals_services (id_referral INT NOT NULL, id_service INT NOT NULL, INDEX IDX_A990808C23DC7195 (id_referral), INDEX IDX_A990808C3F0033A2 (id_service), PRIMARY KEY(id_referral, id_service)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE referrals_services ADD CONSTRAINT FK_A990808C23DC7195 FOREIGN KEY (id_referral) REFERENCES referral (id)');
        $this->addSql('ALTER TABLE referrals_services ADD CONSTRAINT FK_A990808C3F0033A2 FOREIGN KEY (id_service) REFERENCES service (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE referrals_services');
    }
}
