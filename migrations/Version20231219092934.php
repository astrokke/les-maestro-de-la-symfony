<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231219092934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie RENAME INDEX idx_497dd634e8522a2e TO IDX_497DD6345CBD743C');
        $this->addSql('ALTER TABLE photos ADD categorie_id INT DEFAULT NULL, CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_876E0D9BCF5E72D ON photos (categorie_id)');
        $this->addSql('ALTER TABLE users CHANGE role roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie RENAME INDEX idx_497dd6345cbd743c TO IDX_497DD634E8522A2E');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9BCF5E72D');
        $this->addSql('DROP INDEX IDX_876E0D9BCF5E72D ON photos');
        $this->addSql('ALTER TABLE photos DROP categorie_id, CHANGE produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles role JSON NOT NULL');
    }
}
