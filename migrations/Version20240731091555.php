<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731091555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suggestion DROP FOREIGN KEY FK_DD80F31BA71147CC');
        $this->addSql('DROP INDEX IDX_DD80F31BA71147CC ON suggestion');
        $this->addSql('ALTER TABLE suggestion DROP biere_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suggestion ADD biere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BA71147CC FOREIGN KEY (biere_id) REFERENCES biere (id)');
        $this->addSql('CREATE INDEX IDX_DD80F31BA71147CC ON suggestion (biere_id)');
    }
}
