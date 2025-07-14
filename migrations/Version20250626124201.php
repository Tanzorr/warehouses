<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626124201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item RENAME INDEX idx_87ae8c8a88be7ca3 TO IDX_87AE8C8AEB5BE9CB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item RENAME INDEX idx_87ae8c8ade18e50b TO IDX_87AE8C8A4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse CHANGE warehouse name VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE warehouse CHANGE name warehouse VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item RENAME INDEX idx_87ae8c8a4584665a TO IDX_87AE8C8ADE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item RENAME INDEX idx_87ae8c8aeb5be9cb TO IDX_87AE8C8A88BE7CA3
        SQL);
    }
}
