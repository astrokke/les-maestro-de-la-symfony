<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20231219091538.php
final class Version20231219091538 extends AbstractMigration
========
final class Version20231219092611 extends AbstractMigration
>>>>>>>> gael:migrations/Version20231219092611.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20231219091538.php
        $this->addSql('ALTER TABLE admin CHANGE role roles JSON NOT NULL');
        $this->addSql('ALTER TABLE photos CHANGE produit_id produit_id INT DEFAULT NULL');
========
        $this->addSql('ALTER TABLE photos ADD url_photo VARCHAR(255) NOT NULL');
>>>>>>>> gael:migrations/Version20231219092611.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20231219091538.php
        $this->addSql('ALTER TABLE admin CHANGE roles role JSON NOT NULL');
        $this->addSql('ALTER TABLE photos CHANGE produit_id produit_id INT NOT NULL');
========
        $this->addSql('ALTER TABLE photos DROP url_photo');
>>>>>>>> gael:migrations/Version20231219092611.php
    }
}
