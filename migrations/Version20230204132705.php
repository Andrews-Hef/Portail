<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230204132705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_video (id INT AUTO_INCREMENT NOT NULL, libelle_type_video VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE video ADD type_video_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CE43E27B2 FOREIGN KEY (type_video_id) REFERENCES type_video (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2CE43E27B2 ON video (type_video_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CE43E27B2');
        $this->addSql('DROP TABLE type_video');
        $this->addSql('DROP INDEX IDX_7CC7DA2CE43E27B2 ON video');
        $this->addSql('ALTER TABLE video DROP type_video_id');
    }
}
