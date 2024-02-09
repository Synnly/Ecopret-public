<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205161320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `admin` ADD no_compte_id INT NOT NULL');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D762549B4CC FOREIGN KEY (no_compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D762549B4CC ON `admin` (no_compte_id)');
        $this->addSql('ALTER TABLE emprunt ADD id_annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D72D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_364071D72D8F2BF8 ON emprunt (id_annonce_id)');
        $this->addSql('ALTER TABLE prestataire ADD no_utisateur_id INT NOT NULL');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A26480AFB851AB FOREIGN KEY (no_utisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_60A26480AFB851AB ON prestataire (no_utisateur_id)');
        $this->addSql('ALTER TABLE service ADD id_annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD22D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E19D9AD22D8F2BF8 ON service (id_annonce_id)');
        $this->addSql('ALTER TABLE utilisateur ADD no_compte_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32549B4CC FOREIGN KEY (no_compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B32549B4CC ON utilisateur (no_compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D762549B4CC');
        $this->addSql('DROP INDEX UNIQ_880E0D762549B4CC ON `admin`');
        $this->addSql('ALTER TABLE `admin` DROP no_compte_id');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D72D8F2BF8');
        $this->addSql('DROP INDEX UNIQ_364071D72D8F2BF8 ON emprunt');
        $this->addSql('ALTER TABLE emprunt DROP id_annonce_id');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A26480AFB851AB');
        $this->addSql('DROP INDEX UNIQ_60A26480AFB851AB ON prestataire');
        $this->addSql('ALTER TABLE prestataire DROP no_utisateur_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32549B4CC');
        $this->addSql('DROP INDEX UNIQ_1D1C63B32549B4CC ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP no_compte_id');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD22D8F2BF8');
        $this->addSql('DROP INDEX UNIQ_E19D9AD22D8F2BF8 ON service');
        $this->addSql('ALTER TABLE service DROP id_annonce_id');
    }
}
