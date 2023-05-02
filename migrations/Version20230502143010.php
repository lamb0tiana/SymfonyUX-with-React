<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230502143010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_manager ADD team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_manager ADD CONSTRAINT FK_55D548E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55D548E296CD8AE ON team_manager (team_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_manager DROP FOREIGN KEY FK_55D548E296CD8AE');
        $this->addSql('DROP INDEX UNIQ_55D548E296CD8AE ON team_manager');
        $this->addSql('ALTER TABLE team_manager DROP team_id');
    }
}
