<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121140729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout prenom sur joueur et ville sur equipe';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe ADD ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE joueur ADD prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP prenom');
        $this->addSql('ALTER TABLE equipe DROP ville');
    }
}
