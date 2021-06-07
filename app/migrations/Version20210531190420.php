<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531190420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, id_provider INT DEFAULT NULL, title VARCHAR(200) NOT NULL, date_created_at DATETIME NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_1DD39950C21F9F09 (id_provider), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(200) NOT NULL, last_name VARCHAR(200) NOT NULL, dob DATETIME DEFAULT NULL, address LONGTEXT DEFAULT NULL, phone VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, name VARCHAR(200) NOT NULL, address LONGTEXT DEFAULT NULL, email VARCHAR(200) DEFAULT NULL, phone VARCHAR(200) DEFAULT NULL, img_icon_name VARCHAR(200) DEFAULT NULL, UNIQUE INDEX UNIQ_92C4739C5E237E06 (name), INDEX IDX_92C4739C6B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE providers_participants (provider_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_792042F3A53A8AA (provider_id), INDEX IDX_792042F39D1C3019 (participant_id), PRIMARY KEY(provider_id, participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE providers_services (provider_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_D10D5FE5A53A8AA (provider_id), INDEX IDX_D10D5FE5ED5CA9E6 (service_id), PRIMARY KEY(provider_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E19D9AD25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950C21F9F09 FOREIGN KEY (id_provider) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE provider ADD CONSTRAINT FK_92C4739C6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE providers_participants ADD CONSTRAINT FK_792042F3A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE providers_participants ADD CONSTRAINT FK_792042F39D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE providers_services ADD CONSTRAINT FK_D10D5FE5A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE providers_services ADD CONSTRAINT FK_D10D5FE5ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE providers_participants DROP FOREIGN KEY FK_792042F39D1C3019');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD39950C21F9F09');
        $this->addSql('ALTER TABLE providers_participants DROP FOREIGN KEY FK_792042F3A53A8AA');
        $this->addSql('ALTER TABLE providers_services DROP FOREIGN KEY FK_D10D5FE5A53A8AA');
        $this->addSql('ALTER TABLE providers_services DROP FOREIGN KEY FK_D10D5FE5ED5CA9E6');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE providers_participants');
        $this->addSql('DROP TABLE providers_services');
        $this->addSql('DROP TABLE service');
    }
}
