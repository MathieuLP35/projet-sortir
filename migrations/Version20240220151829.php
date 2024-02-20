<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220151829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D5E86FF');
        $this->addSql('DROP INDEX IDX_3BAE0AA7D5E86FF ON event');
        $this->addSql('ALTER TABLE event CHANGE etat_id etats_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7CA7E0C2E FOREIGN KEY (etats_id) REFERENCES etat (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7CA7E0C2E ON event (etats_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7CA7E0C2E');
        $this->addSql('DROP INDEX IDX_3BAE0AA7CA7E0C2E ON event');
        $this->addSql('ALTER TABLE event CHANGE etats_id etat_id INT NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D5E86FF ON event (etat_id)');
    }
}
