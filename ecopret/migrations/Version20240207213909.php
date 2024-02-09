<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207213909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, no_compte_id INT NOT NULL, UNIQUE INDEX UNIQ_880E0D762549B4CC (no_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, nom_annonce VARCHAR(255) NOT NULL, disponibilite VARCHAR(50) NOT NULL, est_rendu TINYINT(1) NOT NULL, est_en_litige TINYINT(1) NOT NULL, image_annonce VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carte_credit (id INT AUTO_INCREMENT NOT NULL, numero_carte INT NOT NULL, date_expiration DATE NOT NULL, code_cvv INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, carte_credit_id INT DEFAULT NULL, nom_compte VARCHAR(255) NOT NULL, prenom_compte VARCHAR(255) DEFAULT NULL, mot_de_passe_compte VARCHAR(255) NOT NULL, adresse_mail_compte VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CFF652602935ED8E (carte_credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte_lieu (compte_id INT NOT NULL, lieu_id INT NOT NULL, INDEX IDX_776F79C5F2C56620 (compte_id), INDEX IDX_776F79C56AB213CC (lieu_id), PRIMARY KEY(compte_id, lieu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte_note (compte_id INT NOT NULL, note_id INT NOT NULL, INDEX IDX_9785FE88F2C56620 (compte_id), INDEX IDX_9785FE8826ED0855 (note_id), PRIMARY KEY(compte_id, note_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte_transaction (compte_id INT NOT NULL, transaction_id INT NOT NULL, INDEX IDX_5E268F85F2C56620 (compte_id), INDEX IDX_5E268F852FC0CB0F (transaction_id), PRIMARY KEY(compte_id, transaction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt (id INT AUTO_INCREMENT NOT NULL, id_annonce_id INT NOT NULL, UNIQUE INDEX UNIQ_364071D72D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieu (id INT AUTO_INCREMENT NOT NULL, nom_lieu VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_dates_annonce (id INT AUTO_INCREMENT NOT NULL, annonce_id INT NOT NULL, date_annonce DATE NOT NULL, id_annonce INT NOT NULL, INDEX IDX_584351E88805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_mots_cles_annonce (id INT AUTO_INCREMENT NOT NULL, annonce_id INT NOT NULL, id_annonce INT NOT NULL, mot_cle VARCHAR(255) NOT NULL, INDEX IDX_8282052A8805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message_notification VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_compte (notification_id INT NOT NULL, compte_id INT NOT NULL, INDEX IDX_B51AC3DEF1A9D84 (notification_id), INDEX IDX_B51AC3DF2C56620 (compte_id), PRIMARY KEY(notification_id, compte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, no_utisateur_id INT NOT NULL, UNIQUE INDEX UNIQ_60A26480AFB851AB (no_utisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, id_annonce_id INT NOT NULL, UNIQUE INDEX UNIQ_E19D9AD22D8F2BF8 (id_annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_723705D18805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, no_compte_id INT NOT NULL, est_verifie TINYINT(1) NOT NULL, est_gele TINYINT(1) NOT NULL, paiement TINYINT(1) NOT NULL, date_de_paiement DATE DEFAULT NULL, date_deb_gel DATE DEFAULT NULL, date_fin_gel DATE DEFAULT NULL, a_une_reduction TINYINT(1) NOT NULL, nb_florains INT NOT NULL, UNIQUE INDEX UNIQ_1D1C63B32549B4CC (no_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `admin` ADD CONSTRAINT FK_880E0D762549B4CC FOREIGN KEY (no_compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652602935ED8E FOREIGN KEY (carte_credit_id) REFERENCES carte_credit (id)');
        $this->addSql('ALTER TABLE compte_lieu ADD CONSTRAINT FK_776F79C5F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_lieu ADD CONSTRAINT FK_776F79C56AB213CC FOREIGN KEY (lieu_id) REFERENCES lieu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_note ADD CONSTRAINT FK_9785FE88F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_note ADD CONSTRAINT FK_9785FE8826ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_transaction ADD CONSTRAINT FK_5E268F85F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_transaction ADD CONSTRAINT FK_5E268F852FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT FK_364071D72D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE liste_dates_annonce ADD CONSTRAINT FK_584351E88805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE liste_mots_cles_annonce ADD CONSTRAINT FK_8282052A8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE notification_compte ADD CONSTRAINT FK_B51AC3DEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_compte ADD CONSTRAINT FK_B51AC3DF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A26480AFB851AB FOREIGN KEY (no_utisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD22D8F2BF8 FOREIGN KEY (id_annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D18805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32549B4CC FOREIGN KEY (no_compte_id) REFERENCES compte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D762549B4CC');
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652602935ED8E');
        $this->addSql('ALTER TABLE compte_lieu DROP FOREIGN KEY FK_776F79C5F2C56620');
        $this->addSql('ALTER TABLE compte_lieu DROP FOREIGN KEY FK_776F79C56AB213CC');
        $this->addSql('ALTER TABLE compte_note DROP FOREIGN KEY FK_9785FE88F2C56620');
        $this->addSql('ALTER TABLE compte_note DROP FOREIGN KEY FK_9785FE8826ED0855');
        $this->addSql('ALTER TABLE compte_transaction DROP FOREIGN KEY FK_5E268F85F2C56620');
        $this->addSql('ALTER TABLE compte_transaction DROP FOREIGN KEY FK_5E268F852FC0CB0F');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY FK_364071D72D8F2BF8');
        $this->addSql('ALTER TABLE liste_dates_annonce DROP FOREIGN KEY FK_584351E88805AB2F');
        $this->addSql('ALTER TABLE liste_mots_cles_annonce DROP FOREIGN KEY FK_8282052A8805AB2F');
        $this->addSql('ALTER TABLE notification_compte DROP FOREIGN KEY FK_B51AC3DEF1A9D84');
        $this->addSql('ALTER TABLE notification_compte DROP FOREIGN KEY FK_B51AC3DF2C56620');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A26480AFB851AB');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD22D8F2BF8');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D18805AB2F');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32549B4CC');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE carte_credit');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE compte_lieu');
        $this->addSql('DROP TABLE compte_note');
        $this->addSql('DROP TABLE compte_transaction');
        $this->addSql('DROP TABLE emprunt');
        $this->addSql('DROP TABLE lieu');
        $this->addSql('DROP TABLE liste_dates_annonce');
        $this->addSql('DROP TABLE liste_mots_cles_annonce');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_compte');
        $this->addSql('DROP TABLE prestataire');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
