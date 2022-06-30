<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Infra\Doctrine\ORM\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220630195201 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fleet (id UUID NOT NULL, "user" UUID NOT NULL, vehicles TEXT, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN fleet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fleet."user" IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN fleet.vehicles IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE fleet');
    }
}
