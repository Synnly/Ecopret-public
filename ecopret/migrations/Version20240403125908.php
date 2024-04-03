<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403125908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_attente_annonce CHANGE no_utilisateur_id no_utilisateur_id INT DEFAULT NULL, CHANGE no_annonce_id no_annonce_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE file_attente_annonce ADD CONSTRAINT FK_E5860ABBBCA70BBF FOREIGN KEY (no_utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE file_attente_annonce ADD CONSTRAINT FK_E5860ABB216E89AE FOREIGN KEY (no_annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E5860ABBBCA70BBF ON file_attente_annonce (no_utilisateur_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E5860ABB216E89AE ON file_attente_annonce (no_annonce_id)');
        $this->addSql('ALTER TABLE notification ADD status INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_attente_annonce DROP FOREIGN KEY FK_E5860ABBBCA70BBF');
        $this->addSql('ALTER TABLE file_attente_annonce DROP FOREIGN KEY FK_E5860ABB216E89AE');
        $this->addSql('DROP INDEX UNIQ_E5860ABBBCA70BBF ON file_attente_annonce');
        $this->addSql('DROP INDEX UNIQ_E5860ABB216E89AE ON file_attente_annonce');
        $this->addSql('ALTER TABLE file_attente_annonce CHANGE no_utilisateur_id no_utilisateur_id INT NOT NULL, CHANGE no_annonce_id no_annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE notification DROP status');
    }
}
