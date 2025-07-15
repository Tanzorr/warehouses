<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625112659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_64C19C1796A8F92 (parent_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE inventory_transaction (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', warehouse_id INT NOT NULL, comment VARCHAR(255) DEFAULT NULL, product_id INT NOT NULL, quantity INT NOT NULL, entity_type VARCHAR(255) NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price NUMERIC(10, 2) NOT NULL, sku VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_reservation (id INT AUTO_INCREMENT NOT NULL, warehouse_id_id INT DEFAULT NULL, product_id INT NOT NULL, quantity INT NOT NULL, reserved_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', released_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', comment VARCHAR(255) DEFAULT NULL, INDEX IDX_EEE7D74AFE25E29A (warehouse_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_reservation_item (id INT AUTO_INCREMENT NOT NULL, product_reservation_id_id INT DEFAULT NULL, product_id_id INT DEFAULT NULL, amount INT NOT NULL, INDEX IDX_87AE8C8A88BE7CA3 (product_reservation_id_id), INDEX IDX_87AE8C8ADE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE stock_availability (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, warehouse_id INT NOT NULL, amount INT NOT NULL, INDEX IDX_1EDC98AC4584665A (product_id), INDEX IDX_1EDC98AC5080ECDE (warehouse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role LONGTEXT NOT NULL COMMENT '(DC2Type:array)', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE warehouse (id INT AUTO_INCREMENT NOT NULL, warehouse VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, location VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE category ADD CONSTRAINT FK_64C19C1796A8F92 FOREIGN KEY (parent_category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation ADD CONSTRAINT FK_EEE7D74AFE25E29A FOREIGN KEY (warehouse_id_id) REFERENCES warehouse (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item ADD CONSTRAINT FK_87AE8C8A88BE7CA3 FOREIGN KEY (product_reservation_id_id) REFERENCES product_reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item ADD CONSTRAINT FK_87AE8C8ADE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_availability ADD CONSTRAINT FK_1EDC98AC4584665A FOREIGN KEY (product_id) REFERENCES product (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_availability ADD CONSTRAINT FK_1EDC98AC5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE category DROP FOREIGN KEY FK_64C19C1796A8F92
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation DROP FOREIGN KEY FK_EEE7D74AFE25E29A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item DROP FOREIGN KEY FK_87AE8C8A88BE7CA3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation_item DROP FOREIGN KEY FK_87AE8C8ADE18E50B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_availability DROP FOREIGN KEY FK_1EDC98AC4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stock_availability DROP FOREIGN KEY FK_1EDC98AC5080ECDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE inventory_transaction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_reservation_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE stock_availability
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE warehouse
        SQL);
    }
}
