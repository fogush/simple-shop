<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210724094259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify cart_product to store count';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE cart_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT FK_2890CCAA1AD5CDBF');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT FK_2890CCAA4584665A');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT cart_product_pkey');
        $this->addSql('ALTER TABLE cart_product ADD id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_product ADD count INT NOT NULL');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT FK_2890CCAA4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_product ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE cart_product_id_seq CASCADE');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT fk_2890ccaa4584665a');
        $this->addSql('ALTER TABLE cart_product DROP CONSTRAINT fk_2890ccaa1ad5cdbf');
        $this->addSql('DROP INDEX cart_product_pkey');
        $this->addSql('ALTER TABLE cart_product DROP id');
        $this->addSql('ALTER TABLE cart_product DROP count');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT fk_2890ccaa4584665a FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_product ADD CONSTRAINT fk_2890ccaa1ad5cdbf FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_product ADD PRIMARY KEY (cart_id, product_id)');
    }
}
