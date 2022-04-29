<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414225523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guidetouristique (id_guide INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, numerotel VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id_guide)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotels (idHotels INT AUTO_INCREMENT NOT NULL, nbetoiles INT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, pointfort VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(idHotels)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id_offre INT AUTO_INCREMENT NOT NULL, destination VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_offre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id_reservation INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, id_offre INT DEFAULT NULL, id_vol INT DEFAULT NULL, id_guidetouristique INT DEFAULT NULL, id_voiture INT DEFAULT NULL, id_hotel INT DEFAULT NULL, date VARCHAR(255) NOT NULL, INDEX IDX_42C849555E5C27E9 (iduser), INDEX IDX_42C849554103C75F (id_offre), INDEX IDX_42C8495597F87FB1 (id_vol), INDEX IDX_42C849557B7328CF (id_guidetouristique), INDEX IDX_42C84955377F287F (id_voiture), INDEX IDX_42C84955EDD61FE9 (id_hotel), PRIMARY KEY(id_reservation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societedelocation (idSoclocation INT AUTO_INCREMENT NOT NULL, nomdesoc VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(idSoclocation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voitures (idVoiture INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, nbsieges INT NOT NULL, image VARCHAR(255) NOT NULL, idSoclocation INT DEFAULT NULL, INDEX IDX_8B58301B32A4CE58 (idSoclocation), PRIMARY KEY(idVoiture)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol (id_vol INT AUTO_INCREMENT NOT NULL, destination VARCHAR(255) NOT NULL, date_aller DATE NOT NULL, date_retour DATE NOT NULL, classe VARCHAR(255) NOT NULL, nombre INT NOT NULL, compagnie_aerienne VARCHAR(255) NOT NULL, type_vol VARCHAR(255) NOT NULL, PRIMARY KEY(id_vol)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849555E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554103C75F FOREIGN KEY (id_offre) REFERENCES offre (id_offre)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495597F87FB1 FOREIGN KEY (id_vol) REFERENCES vol (id_vol)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557B7328CF FOREIGN KEY (id_guidetouristique) REFERENCES guidetouristique (id_guide)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955377F287F FOREIGN KEY (id_voiture) REFERENCES voitures (idVoiture)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955EDD61FE9 FOREIGN KEY (id_hotel) REFERENCES hotels (idHotels)');
        $this->addSql('ALTER TABLE voitures ADD CONSTRAINT FK_8B58301B32A4CE58 FOREIGN KEY (idSoclocation) REFERENCES societedelocation (idSoclocation)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557B7328CF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955EDD61FE9');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554103C75F');
        $this->addSql('ALTER TABLE voitures DROP FOREIGN KEY FK_8B58301B32A4CE58');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955377F287F');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495597F87FB1');
        $this->addSql('DROP TABLE guidetouristique');
        $this->addSql('DROP TABLE hotels');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE societedelocation');
        $this->addSql('DROP TABLE voitures');
        $this->addSql('DROP TABLE vol');
    }
}
