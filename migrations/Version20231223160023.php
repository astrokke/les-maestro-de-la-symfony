<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231223160023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94DE7DC5C');
        $this->addSql('DROP INDEX IDX_1483A5E94DE7DC5C ON users');
        $this->addSql('ALTER TABLE users DROP adresse_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD adresse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E94DE7DC5C ON users (adresse_id)');
    }
}
