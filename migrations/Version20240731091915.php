<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731091915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biere_suggestion (biere_id INT NOT NULL, suggestion_id INT NOT NULL, INDEX IDX_D17D3021A71147CC (biere_id), INDEX IDX_D17D3021A41BB822 (suggestion_id), PRIMARY KEY(biere_id, suggestion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biere_suggestion ADD CONSTRAINT FK_D17D3021A71147CC FOREIGN KEY (biere_id) REFERENCES biere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE biere_suggestion ADD CONSTRAINT FK_D17D3021A41BB822 FOREIGN KEY (suggestion_id) REFERENCES suggestion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere_suggestion DROP FOREIGN KEY FK_D17D3021A71147CC');
        $this->addSql('ALTER TABLE biere_suggestion DROP FOREIGN KEY FK_D17D3021A41BB822');
        $this->addSql('DROP TABLE biere_suggestion');
    }
}
