<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427003836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambres (id INT AUTO_INCREMENT NOT NULL, hotels_id INT NOT NULL, type_chambre VARCHAR(255) NOT NULL, nbr_lit INT NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_36C92962F42F66C8 (hotels_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chambres ADD CONSTRAINT FK_36C92962F42F66C8 FOREIGN KEY (hotels_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE voiture CHANGE matricule matricule VARCHAR(1000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chambres');
        $this->addSql('ALTER TABLE voiture CHANGE matricule matricule VARCHAR(255) NOT NULL');
    }
}
