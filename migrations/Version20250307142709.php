<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307142709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE players (id UUID NOT NULL, team_id UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, age INT NOT NULL, player_number INT NOT NULL, position VARCHAR(255) NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_264E43A6990898A ON players (player_number)');
        $this->addSql('CREATE INDEX IDX_264E43A6296CD8AE ON players (team_id)');
        $this->addSql('COMMENT ON COLUMN players.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN players.team_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE teams (id UUID NOT NULL, name VARCHAR(100) NOT NULL, city VARCHAR(100) NOT NULL, year_founded INT NOT NULL, stadium VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C222585E237E06 ON teams (name)');
        $this->addSql('COMMENT ON COLUMN teams.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE players ADD CONSTRAINT FK_264E43A6296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE players DROP CONSTRAINT FK_264E43A6296CD8AE');
        $this->addSql('DROP TABLE players');
        $this->addSql('DROP TABLE teams');
    }
}
