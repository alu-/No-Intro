<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201225140413 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, giantbomb_guid VARCHAR(16) NOT NULL, original_release_date DATE DEFAULT NULL, UNIQUE INDEX UNIQ_232B318C9B51E4D6 (giantbomb_guid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_image (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, type enum(\'icon_url\', \'medium_url\', \'original_url\', \'screen_url\', \'screen_large_url\', \'small_url\', \'super_url\', \'thumb_url\', \'tiny_url\'), url VARCHAR(512) NOT NULL, is_downloaded TINYINT(1) NOT NULL, INDEX IDX_F70E7DD0E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_image ADD CONSTRAINT FK_F70E7DD0E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_image DROP FOREIGN KEY FK_F70E7DD0E48FD905');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_image');
    }
}
