<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205160224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte ADD carte_credit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652602935ED8E FOREIGN KEY (carte_credit_id) REFERENCES carte_credit (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFF652602935ED8E ON compte (carte_credit_id)');
        $this->addSql('ALTER TABLE liste_dates_annonce ADD annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE liste_dates_annonce ADD CONSTRAINT FK_584351E88805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_584351E88805AB2F ON liste_dates_annonce (annonce_id)');
        $this->addSql('ALTER TABLE liste_mots_cles_annonce ADD annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE liste_mots_cles_annonce ADD CONSTRAINT FK_8282052A8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_8282052A8805AB2F ON liste_mots_cles_annonce (annonce_id)');
        $this->addSql('ALTER TABLE transaction ADD annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D18805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_723705D18805AB2F ON transaction (annonce_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_dates_annonce DROP FOREIGN KEY FK_584351E88805AB2F');
        $this->addSql('DROP INDEX IDX_584351E88805AB2F ON liste_dates_annonce');
        $this->addSql('ALTER TABLE liste_dates_annonce DROP annonce_id');
        $this->addSql('ALTER TABLE liste_mots_cles_annonce DROP FOREIGN KEY FK_8282052A8805AB2F');
        $this->addSql('DROP INDEX IDX_8282052A8805AB2F ON liste_mots_cles_annonce');
        $this->addSql('ALTER TABLE liste_mots_cles_annonce DROP annonce_id');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652602935ED8E');
        $this->addSql('DROP INDEX UNIQ_CFF652602935ED8E ON compte');
        $this->addSql('ALTER TABLE compte DROP carte_credit_id');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D18805AB2F');
        $this->addSql('DROP INDEX IDX_723705D18805AB2F ON transaction');
        $this->addSql('ALTER TABLE transaction DROP annonce_id');
    }
}
