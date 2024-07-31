<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731085218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere ADD origine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD187998E FOREIGN KEY (origine_id) REFERENCES origine (id)');
        $this->addSql('CREATE INDEX IDX_D33ECD187998E ON biere (origine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD187998E');
        $this->addSql('DROP INDEX IDX_D33ECD187998E ON biere');
        $this->addSql('ALTER TABLE biere DROP origine_id');
    }
}
