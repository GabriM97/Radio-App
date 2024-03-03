<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113172054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE download (id INT AUTO_INCREMENT NOT NULL, episode_id INT NOT NULL, datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_781A8270362B62A0 (episode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, podcast_id INT NOT NULL, title VARCHAR(255) NOT NULL, topic VARCHAR(255) NOT NULL,/* INDEX IDX_DDAA1CDA786136AB (podcast_id),*/ PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE download ADD CONSTRAINT FK_781A8270362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        // $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE download DROP FOREIGN KEY FK_781A8270362B62A0');
        // $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA786136AB');
        $this->addSql('DROP TABLE download');
        $this->addSql('DROP TABLE episode');
    }
}
