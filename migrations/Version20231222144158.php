<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231222144158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF2EFD822');
        $this->addSql('DROP INDEX IDX_6EEAA67DAC56B862 ON commande');
        $this->addSql('ALTER TABLE commande CHANGE est_livré_id est_livre_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DAC56B862 FOREIGN KEY (est_livre_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DAC56B862 ON commande (est_livre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DAC56B862');
        $this->addSql('DROP INDEX IDX_6EEAA67DAC56B862 ON commande');
        $this->addSql('ALTER TABLE commande CHANGE est_livre_id est_livré_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF2EFD822 FOREIGN KEY (est_livré_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DAC56B862 ON commande (est_livré_id)');
    }
}
