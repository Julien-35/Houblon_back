<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731143408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD1BCF5E72D');
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD1DCD6110');
        $this->addSql('DROP INDEX UNIQ_D33ECD1DCD6110 ON biere');
        $this->addSql('DROP INDEX IDX_D33ECD1BCF5E72D ON biere');
        $this->addSql('ALTER TABLE biere DROP stock_id, DROP categorie_id');
        $this->addSql('ALTER TABLE categorie DROP name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere ADD stock_id INT DEFAULT NULL, ADD categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD1BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD1DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D33ECD1DCD6110 ON biere (stock_id)');
        $this->addSql('CREATE INDEX IDX_D33ECD1BCF5E72D ON biere (categorie_id)');
        $this->addSql('ALTER TABLE categorie ADD name VARCHAR(250) NOT NULL');
    }
}
