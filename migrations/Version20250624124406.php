<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250624124406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE stock_availability (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, warehouse_id INT NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP parent_category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD warehouse_id INT NOT NULL, DROP stock_quantity, DROP wearhouse_id, CHANGE price price NUMERIC(10, 2) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE stock_availability
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category ADD parent_category INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_D34A04AD12469DE2 ON product
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD wearhouse_id INT NOT NULL, CHANGE price price NUMERIC(10, 0) NOT NULL, CHANGE warehouse_id stock_quantity INT NOT NULL
        SQL);
    }
}
