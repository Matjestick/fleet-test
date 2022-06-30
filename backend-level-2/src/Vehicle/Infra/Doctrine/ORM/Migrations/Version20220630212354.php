<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Infra\Doctrine\ORM\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220630212354 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE vehicle (id UUID NOT NULL, fleets TEXT, latitude NUMERIC(20, 16) DEFAULT NULL, longitude NUMERIC(20, 16) DEFAULT NULL, altitude NUMERIC(20, 16) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN vehicle.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN vehicle.fleets IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE vehicle');
    }
}
