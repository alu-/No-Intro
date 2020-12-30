<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201230001957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE FULLTEXT INDEX IDX_232B318C5E237E065F12BB39 ON game (name, aliases)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX IDX_232B318C5E237E065F12BB39 ON game');
    }
}
