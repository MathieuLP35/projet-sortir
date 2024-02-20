<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220100253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_user (event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92589AE271F7E88B (event_id), INDEX IDX_92589AE2A76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (no_site INT AUTO_INCREMENT NOT NULL, name_site VARCHAR(30) NOT NULL, PRIMARY KEY(no_site)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE271F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user ADD CONSTRAINT FK_92589AE2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sites');
        $this->addSql('ALTER TABLE event ADD etats_id INT NOT NULL, ADD places_id INT NOT NULL, ADD sites_id INT NOT NULL, ADD organiser_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7CA7E0C2E FOREIGN KEY (etats_id) REFERENCES etat (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA78317B347 FOREIGN KEY (places_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77838E496 FOREIGN KEY (sites_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A0631C12 FOREIGN KEY (organiser_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7CA7E0C2E ON event (etats_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA78317B347 ON event (places_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA77838E496 ON event (sites_id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7A0631C12 ON event (organiser_id)');
        $this->addSql('ALTER TABLE place ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_741D53CD8BAC62AF ON place (city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77838E496');
        $this->addSql('CREATE TABLE sites (no_site INT AUTO_INCREMENT NOT NULL, name_site VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(no_site)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE271F7E88B');
        $this->addSql('ALTER TABLE event_user DROP FOREIGN KEY FK_92589AE2A76ED395');
        $this->addSql('DROP TABLE event_user');
        $this->addSql('DROP TABLE site');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD8BAC62AF');
        $this->addSql('DROP INDEX IDX_741D53CD8BAC62AF ON place');
        $this->addSql('ALTER TABLE place DROP city_id');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7CA7E0C2E');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA78317B347');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A0631C12');
        $this->addSql('DROP INDEX IDX_3BAE0AA7CA7E0C2E ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA78317B347 ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA77838E496 ON event');
        $this->addSql('DROP INDEX IDX_3BAE0AA7A0631C12 ON event');
        $this->addSql('ALTER TABLE event DROP etats_id, DROP places_id, DROP sites_id, DROP organiser_id');
    }
}
