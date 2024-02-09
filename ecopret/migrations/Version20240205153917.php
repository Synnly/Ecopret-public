<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205153917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte_note (compte_id INT NOT NULL, note_id INT NOT NULL, INDEX IDX_9785FE88F2C56620 (compte_id), INDEX IDX_9785FE8826ED0855 (note_id), PRIMARY KEY(compte_id, note_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte_transaction (compte_id INT NOT NULL, transaction_id INT NOT NULL, INDEX IDX_5E268F85F2C56620 (compte_id), INDEX IDX_5E268F852FC0CB0F (transaction_id), PRIMARY KEY(compte_id, transaction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte_note ADD CONSTRAINT FK_9785FE88F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_note ADD CONSTRAINT FK_9785FE8826ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_transaction ADD CONSTRAINT FK_5E268F85F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compte_transaction ADD CONSTRAINT FK_5E268F852FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_note DROP FOREIGN KEY FK_9785FE88F2C56620');
        $this->addSql('ALTER TABLE compte_note DROP FOREIGN KEY FK_9785FE8826ED0855');
        $this->addSql('ALTER TABLE compte_transaction DROP FOREIGN KEY FK_5E268F85F2C56620');
        $this->addSql('ALTER TABLE compte_transaction DROP FOREIGN KEY FK_5E268F852FC0CB0F');
        $this->addSql('DROP TABLE compte_note');
        $this->addSql('DROP TABLE compte_transaction');
    }
}
