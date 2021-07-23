<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210723175336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `name` to be a real unique field of a product while `title` can be duplicated';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product ADD name VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX unique_name ON product (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX unique_name');
        $this->addSql('ALTER TABLE product DROP name');
    }
}
