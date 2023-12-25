<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231225144948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adresse ADD code_postal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F081667B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT FK_C35F0816B2B59251 FOREIGN KEY (code_postal_id) REFERENCES code_postal (id)');
        $this->addSql('CREATE INDEX IDX_C35F081667B3B43D ON adresse (users_id)');
        $this->addSql('CREATE INDEX IDX_C35F0816B2B59251 ON adresse (code_postal_id)');
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
      
        $this->addSql('ALTER TABLE code_postal CHANGE libelle libelle VARCHAR(10) DEFAULT NULL');
    }
}
