<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625133634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation DROP FOREIGN KEY FK_EEE7D74AFE25E29A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EEE7D74AFE25E29A ON product_reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation DROP product_id, DROP quantity, CHANGE warehouse_id_id warehouse_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation ADD CONSTRAINT FK_EEE7D74A5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EEE7D74A5080ECDE ON product_reservation (warehouse_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation DROP FOREIGN KEY FK_EEE7D74A5080ECDE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EEE7D74A5080ECDE ON product_reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation ADD product_id INT NOT NULL, ADD quantity INT NOT NULL, CHANGE warehouse_id warehouse_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_reservation ADD CONSTRAINT FK_EEE7D74AFE25E29A FOREIGN KEY (warehouse_id_id) REFERENCES warehouse (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EEE7D74AFE25E29A ON product_reservation (warehouse_id_id)
        SQL);
    }
}
