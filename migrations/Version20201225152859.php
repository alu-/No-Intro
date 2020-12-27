<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201225152859 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD aliases LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_image CHANGE type type enum(\'icon_url\', \'medium_url\', \'original_url\', \'screen_url\', \'screen_large_url\', \'small_url\', \'super_url\', \'thumb_url\', \'tiny_url\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP aliases');
        $this->addSql('ALTER TABLE game_image CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
