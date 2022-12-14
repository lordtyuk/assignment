<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214082654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ram_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE storage_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency (id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6956883F77153098 ON currency (code)');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB77153098 ON location (code)');
        $this->addSql('CREATE TABLE model (id INT NOT NULL, ram_type_id INT DEFAULT NULL, storage_type_id INT DEFAULT NULL, location_id INT DEFAULT NULL, currency_id INT DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, ram_count INT NOT NULL, storage_count INT NOT NULL, storage_size_gb INT NOT NULL, price NUMERIC(10, 0) NOT NULL, usd_price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79572D95E237E06 ON model (name)');
        $this->addSql('CREATE INDEX IDX_D79572D98AE6E1E4 ON model (ram_type_id)');
        $this->addSql('CREATE INDEX IDX_D79572D9B270BFF1 ON model (storage_type_id)');
        $this->addSql('CREATE INDEX IDX_D79572D964D218E ON model (location_id)');
        $this->addSql('CREATE INDEX IDX_D79572D938248176 ON model (currency_id)');
        $this->addSql('CREATE TABLE ram_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_525C27E5E237E06 ON ram_type (name)');
        $this->addSql('CREATE TABLE storage_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_85F39C3C5E237E06 ON storage_type (name)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D98AE6E1E4 FOREIGN KEY (ram_type_id) REFERENCES ram_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9B270BFF1 FOREIGN KEY (storage_type_id) REFERENCES storage_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D964D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D938248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE currency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE model_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ram_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE storage_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE model DROP CONSTRAINT FK_D79572D98AE6E1E4');
        $this->addSql('ALTER TABLE model DROP CONSTRAINT FK_D79572D9B270BFF1');
        $this->addSql('ALTER TABLE model DROP CONSTRAINT FK_D79572D964D218E');
        $this->addSql('ALTER TABLE model DROP CONSTRAINT FK_D79572D938248176');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE ram_type');
        $this->addSql('DROP TABLE storage_type');
    }
}
