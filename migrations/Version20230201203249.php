<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201203249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, libelle_abonnement VARCHAR(255) NOT NULL, qualite_max INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_video (categorie_id INT NOT NULL, video_id INT NOT NULL, INDEX IDX_89BF37FCBCF5E72D (categorie_id), INDEX IDX_89BF37FC29C1004E (video_id), PRIMARY KEY(categorie_id, video_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, videoscom_id INT DEFAULT NULL, users_id INT DEFAULT NULL, texte VARCHAR(255) NOT NULL, INDEX IDX_67F068BC44C6FC12 (videoscom_id), INDEX IDX_67F068BC67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, libelle_role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, libelle_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, abonnement_id INT DEFAULT NULL, role_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649F1D74413 (abonnement_id), INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, types_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, qualite INT NOT NULL, INDEX IDX_7CC7DA2C8EB23357 (types_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_video ADD CONSTRAINT FK_89BF37FCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_video ADD CONSTRAINT FK_89BF37FC29C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC44C6FC12 FOREIGN KEY (videoscom_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C8EB23357 FOREIGN KEY (types_id) REFERENCES type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_video DROP FOREIGN KEY FK_89BF37FCBCF5E72D');
        $this->addSql('ALTER TABLE categorie_video DROP FOREIGN KEY FK_89BF37FC29C1004E');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC44C6FC12');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC67B3B43D');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F1D74413');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C8EB23357');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_video');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE video');
    }
}
