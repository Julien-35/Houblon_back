<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731143507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere ADD stock_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD1DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D33ECD1DCD6110 ON biere (stock_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD1DCD6110');
        $this->addSql('DROP INDEX UNIQ_D33ECD1DCD6110 ON biere');
        $this->addSql('ALTER TABLE biere DROP stock_id');
    }
}
