<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313200907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE litige (id INT AUTO_INCREMENT NOT NULL, plaignant_id INT NOT NULL, accuse_id INT NOT NULL, transaction_id INT NOT NULL, admin_id INT DEFAULT NULL, statut INT NOT NULL, description VARCHAR(1024) DEFAULT NULL, est_valide TINYINT(1) DEFAULT NULL, INDEX IDX_EEE9D46D52E4BBF5 (plaignant_id), INDEX IDX_EEE9D46D394E6D17 (accuse_id), INDEX IDX_EEE9D46D2FC0CB0F (transaction_id), INDEX IDX_EEE9D46D642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE litige ADD CONSTRAINT FK_EEE9D46D52E4BBF5 FOREIGN KEY (plaignant_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE litige ADD CONSTRAINT FK_EEE9D46D394E6D17 FOREIGN KEY (accuse_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE litige ADD CONSTRAINT FK_EEE9D46D2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE litige ADD CONSTRAINT FK_EEE9D46D642B8210 FOREIGN KEY (admin_id) REFERENCES `admin` (id)');
        $this->addSql('ALTER TABLE annonce DROP est_rendu, DROP est_en_litige');
        $this->addSql('ALTER TABLE transaction ADD prestataire_id INT NOT NULL, ADD client_id INT NOT NULL, ADD est_cloture TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D119EB6921 FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_723705D1BE3DB2B7 ON transaction (prestataire_id)');
        $this->addSql('CREATE INDEX IDX_723705D119EB6921 ON transaction (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE litige DROP FOREIGN KEY FK_EEE9D46D52E4BBF5');
        $this->addSql('ALTER TABLE litige DROP FOREIGN KEY FK_EEE9D46D394E6D17');
        $this->addSql('ALTER TABLE litige DROP FOREIGN KEY FK_EEE9D46D2FC0CB0F');
        $this->addSql('ALTER TABLE litige DROP FOREIGN KEY FK_EEE9D46D642B8210');
        $this->addSql('DROP TABLE litige');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1BE3DB2B7');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D119EB6921');
        $this->addSql('DROP INDEX IDX_723705D1BE3DB2B7 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D119EB6921 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP prestataire_id, DROP client_id, DROP est_cloture');
        $this->addSql('ALTER TABLE annonce ADD est_rendu TINYINT(1) NOT NULL, ADD est_en_litige TINYINT(1) NOT NULL');
    }
}
