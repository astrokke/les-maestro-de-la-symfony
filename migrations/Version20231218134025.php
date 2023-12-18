<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218134025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE role role JSON NOT NULL');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634E8522A2E');
        $this->addSql('DROP INDEX IDX_497DD634E8522A2E ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE categorie_enfant_id categorie_parente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD6345CBD743C FOREIGN KEY (categorie_parente_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_497DD6345CBD743C ON categorie (categorie_parente_id)');
        $this->addSql('ALTER TABLE photos ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_876E0D9BCF5E72D ON photos (categorie_id)');
        $this->addSql('ALTER TABLE users CHANGE role role JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin CHANGE role role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY FK_497DD6345CBD743C');
        $this->addSql('DROP INDEX IDX_497DD6345CBD743C ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE categorie_parente_id categorie_enfant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634E8522A2E FOREIGN KEY (categorie_enfant_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_497DD634E8522A2E ON categorie (categorie_enfant_id)');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9BCF5E72D');
        $this->addSql('DROP INDEX IDX_876E0D9BCF5E72D ON photos');
        $this->addSql('ALTER TABLE photos DROP categorie_id');
        $this->addSql('ALTER TABLE users CHANGE role role VARCHAR(255) NOT NULL');
    }
}
