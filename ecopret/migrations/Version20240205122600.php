<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240205122600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_compte (notification_id INT NOT NULL, compte_id INT NOT NULL, INDEX IDX_B51AC3DEF1A9D84 (notification_id), INDEX IDX_B51AC3DF2C56620 (compte_id), PRIMARY KEY(notification_id, compte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_compte ADD CONSTRAINT FK_B51AC3DEF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_compte ADD CONSTRAINT FK_B51AC3DF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_compte DROP FOREIGN KEY FK_B51AC3DEF1A9D84');
        $this->addSql('ALTER TABLE notification_compte DROP FOREIGN KEY FK_B51AC3DF2C56620');
        $this->addSql('DROP TABLE notification_compte');
    }
}
