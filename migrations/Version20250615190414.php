<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250615190414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_transaction DROP FOREIGN KEY FK_6C5391E4584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6C5391E4584665A ON inventory_transaction
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_transaction ADD reservation_id INT NOT NULL, DROP product_id, DROP quantity, DROP source_type, DROP source_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_transaction ADD quantity INT NOT NULL, ADD source_type VARCHAR(50) NOT NULL, ADD source_id INT NOT NULL, CHANGE reservation_id product_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE inventory_transaction ADD CONSTRAINT FK_6C5391E4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6C5391E4584665A ON inventory_transaction (product_id)
        SQL);
    }
}
