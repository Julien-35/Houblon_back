<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731131858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD1C54C8C93');
        $this->addSql('ALTER TABLE biere_suggestion DROP FOREIGN KEY FK_D17D3021A71147CC');
        $this->addSql('ALTER TABLE biere_suggestion DROP FOREIGN KEY FK_D17D3021A41BB822');
        $this->addSql('DROP TABLE biere_suggestion');
        $this->addSql('DROP TABLE type');
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD1DCD6110');
        $this->addSql('ALTER TABLE biere DROP FOREIGN KEY FK_D33ECD187998E');
        $this->addSql('DROP INDEX IDX_D33ECD187998E ON biere');
        $this->addSql('DROP INDEX IDX_D33ECD1DCD6110 ON biere');
        $this->addSql('DROP INDEX IDX_D33ECD1C54C8C93 ON biere');
        $this->addSql('ALTER TABLE biere DROP origine_id, DROP stock_id, DROP type_id');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660A76ED395');
        $this->addSql('DROP INDEX IDX_4B365660A76ED395 ON stock');
        $this->addSql('ALTER TABLE stock DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE biere_suggestion (biere_id INT NOT NULL, suggestion_id INT NOT NULL, INDEX IDX_D17D3021A41BB822 (suggestion_id), INDEX IDX_D17D3021A71147CC (biere_id), PRIMARY KEY(biere_id, suggestion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE biere_suggestion ADD CONSTRAINT FK_D17D3021A71147CC FOREIGN KEY (biere_id) REFERENCES biere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE biere_suggestion ADD CONSTRAINT FK_D17D3021A41BB822 FOREIGN KEY (suggestion_id) REFERENCES suggestion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE biere ADD origine_id INT DEFAULT NULL, ADD stock_id INT DEFAULT NULL, ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD1C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD1DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE biere ADD CONSTRAINT FK_D33ECD187998E FOREIGN KEY (origine_id) REFERENCES origine (id)');
        $this->addSql('CREATE INDEX IDX_D33ECD187998E ON biere (origine_id)');
        $this->addSql('CREATE INDEX IDX_D33ECD1DCD6110 ON biere (stock_id)');
        $this->addSql('CREATE INDEX IDX_D33ECD1C54C8C93 ON biere (type_id)');
        $this->addSql('ALTER TABLE stock ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4B365660A76ED395 ON stock (user_id)');
    }
}
