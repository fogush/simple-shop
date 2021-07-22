<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210722182615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add the cart and product entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE cart (
                id INT NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('
            CREATE TABLE cart_product (
                cart_id INT NOT NULL, 
                product_id INT NOT NULL, 
                PRIMARY KEY(cart_id, product_id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_2890CCAA1AD5CDBF ON cart_product (cart_id)');
        $this->addSql('CREATE INDEX IDX_2890CCAA4584665A ON cart_product (product_id)');
        $this->addSql('
            CREATE TABLE product (
                id INT NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                price_cents INT NOT NULL, 
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('
            ALTER TABLE cart_product 
                ADD CONSTRAINT FK_2890CCAA1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE cart_product 
                ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT FK_2890CCAA1AD5CDBF');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT FK_2890CCAA4584665A');
        $this->addSql('DROP SEQUENCE cart_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_product');
        $this->addSql('DROP TABLE product');
    }
}
