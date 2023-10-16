<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011132541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tools ADD user_tool_id INT NOT NULL');
        $this->addSql('ALTER TABLE tools ADD CONSTRAINT FK_EAFADE77D7763CC6 FOREIGN KEY (user_tool_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EAFADE77D7763CC6 ON tools (user_tool_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tools DROP CONSTRAINT FK_EAFADE77D7763CC6');
        $this->addSql('DROP INDEX IDX_EAFADE77D7763CC6');
        $this->addSql('ALTER TABLE tools DROP user_tool_id');
    }
}
