<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205123657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte_lieu (compte_id INT NOT NULL, lieu_id INT NOT NULL, INDEX IDX_776F79C5F2C56620 (compte_id), INDEX IDX_776F79C56AB213CC (lieu_id), PRIMARY KEY(compte_id, lieu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte_lieu ADD CONSTRAINT FK_776F79C5F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_lieu ADD CONSTRAINT FK_776F79C56AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_lieu DROP FOREIGN KEY FK_776F79C5F2C56620');
        $this->addSql('ALTER TABLE compte_lieu DROP FOREIGN KEY FK_776F79C56AB213CC');
        $this->addSql('DROP TABLE compte_lieu');
    }
}
