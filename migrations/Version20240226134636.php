<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226134636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77838E496');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA78317B347');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7CA7E0C2E');
        $this->addSql('DROP INDEX IDX_3BAE0AA7CA7E0C2E ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA78317B347 ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA77838E496 ON event');
        $this->addSql('ALTER TABLE event ADD etat_id INT NOT NULL, ADD place_id INT NOT NULL, ADD site_id INT NOT NULL, DROP etats_id, DROP places_id, DROP sites_id');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7D5E86FF FOREIGN KEY (etat_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7D5E86FF ON event (etat_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7DA6A219 ON event (place_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F6BD1646 ON event (site_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7D5E86FF');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7DA6A219');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F6BD1646');
        $this->addSql('DROP INDEX IDX_3BAE0AA7D5E86FF ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA7DA6A219 ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA7F6BD1646 ON event');
        $this->addSql('ALTER TABLE event ADD etats_id INT NOT NULL, ADD places_id INT NOT NULL, ADD sites_id INT NOT NULL, DROP etat_id, DROP place_id, DROP site_id');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77838E496 FOREIGN KEY (sites_id) REFERENCES site (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA78317B347 FOREIGN KEY (places_id) REFERENCES place (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7CA7E0C2E FOREIGN KEY (etats_id) REFERENCES etat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7CA7E0C2E ON event (etats_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA78317B347 ON event (places_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77838E496 ON event (sites_id)');
    }
}
