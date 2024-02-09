<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205104607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP type_annonce, DROP mots_cles_annonce, DROP dates_annonce');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD type_annonce VARCHAR(255) NOT NULL, ADD mots_cles_annonce LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD dates_annonce LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
