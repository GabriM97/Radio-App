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
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, podcast_id INT NOT NULL, title VARCHAR(255) NOT NULL, topic VARCHAR(255) NOT NULL, INDEX IDX_DDAA1CDA786136AB (podcast_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podcast (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id), UNIQUE (email)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_episode (user_id INT NOT NULL, episode_id INT NOT NULL, INDEX IDX_85A702D0A76ED395 (user_id), INDEX IDX_85A702D0362B62A0 (episode_id), PRIMARY KEY(user_id, episode_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE download ADD CONSTRAINT FK_781A8270362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA786136AB FOREIGN KEY (podcast_id) REFERENCES podcast (id)');
        $this->addSql('ALTER TABLE user_episode ADD CONSTRAINT FK_85A702D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_episode ADD CONSTRAINT FK_85A702D0362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE download DROP FOREIGN KEY FK_781A8270362B62A0');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA786136AB');
        $this->addSql('ALTER TABLE user_episode DROP FOREIGN KEY FK_85A702D0A76ED395');
        $this->addSql('ALTER TABLE user_episode DROP FOREIGN KEY FK_85A702D0362B62A0');
        $this->addSql('DROP TABLE download');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE podcast');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_episode');
    }
}
