<?php

declare(strict_types=1);

namespace MyProject\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210810062204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE countries (id INT AUTO_INCREMENT NOT NULL, country_name VARCHAR(100) NOT NULL, iso3 VARCHAR(3) NOT NULL, phone_code INT NOT NULL, UNIQUE INDEX UNIQ_5D66EBADD910F5E2 (country_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_confirmation_attempts (id BIGINT AUTO_INCREMENT NOT NULL, phone_confirmation_id BIGINT DEFAULT NULL, input_confirmation_code INT NOT NULL, status VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_38042FC97064BDBF (phone_confirmation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone_confirmations (id BIGINT AUTO_INCREMENT NOT NULL, transaction_id BIGINT DEFAULT NULL, confirmation_code INT NOT NULL, status VARCHAR(30) NOT NULL, confirmed_at DATETIME DEFAULT NULL, confirmation_code_message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C5835DED2FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phones (id BIGINT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, phone_number BIGINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E3282EF5F92F3E70 (country_id), UNIQUE INDEX unique_phone (country_id, phone_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id BIGINT AUTO_INCREMENT NOT NULL, user_id BIGINT DEFAULT NULL, phone_id BIGINT DEFAULT NULL, status VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, confirmed_at DATETIME DEFAULT NULL, success_message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EAA81A4CA76ED395 (user_id), INDEX IDX_EAA81A4C3B7323CB (phone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id BIGINT AUTO_INCREMENT NOT NULL, email VARCHAR(150) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phone_confirmation_attempts ADD CONSTRAINT FK_38042FC97064BDBF FOREIGN KEY (phone_confirmation_id) REFERENCES phone_confirmations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE phone_confirmations ADD CONSTRAINT FK_C5835DED2FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transactions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE phones ADD CONSTRAINT FK_E3282EF5F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C3B7323CB FOREIGN KEY (phone_id) REFERENCES phones (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE phones DROP FOREIGN KEY FK_E3282EF5F92F3E70');
        $this->addSql('ALTER TABLE phone_confirmation_attempts DROP FOREIGN KEY FK_38042FC97064BDBF');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C3B7323CB');
        $this->addSql('ALTER TABLE phone_confirmations DROP FOREIGN KEY FK_C5835DED2FC0CB0F');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA76ED395');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE phone_confirmation_attempts');
        $this->addSql('DROP TABLE phone_confirmations');
        $this->addSql('DROP TABLE phones');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
    }
}
