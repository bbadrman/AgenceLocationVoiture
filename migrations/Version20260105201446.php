<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105201446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, customer_id INT NOT NULL, pickup_point_id INT NOT NULL, return_point_id INT DEFAULT NULL, booking_number VARCHAR(50) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, number_of_days INT NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, total_price NUMERIC(10, 2) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', notes LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_E00CEDDE2D0A24AA (booking_number), INDEX IDX_E00CEDDEC3C6F69F (car_id), INDEX IDX_E00CEDDE9395C3F3 (customer_id), INDEX IDX_E00CEDDE682033F1 (pickup_point_id), INDEX IDX_E00CEDDED65229F5 (return_point_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, brand VARCHAR(100) NOT NULL, model VARCHAR(100) NOT NULL, year VARCHAR(4) DEFAULT NULL, seats INT NOT NULL, fuel_type VARCHAR(20) NOT NULL, transmission VARCHAR(20) NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, description LONGTEXT DEFAULT NULL, features JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', images JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', main_image VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, registration_number VARCHAR(50) DEFAULT NULL, INDEX IDX_773DE69D8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_81398E09E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pickup_point (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(150) NOT NULL, type VARCHAR(20) NOT NULL, address LONGTEXT DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_1467BEE88BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE682033F1 FOREIGN KEY (pickup_point_id) REFERENCES pickup_point (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDED65229F5 FOREIGN KEY (return_point_id) REFERENCES pickup_point (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE pickup_point ADD CONSTRAINT FK_1467BEE88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEC3C6F69F');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE9395C3F3');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE682033F1');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDED65229F5');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D8BAC62AF');
        $this->addSql('ALTER TABLE pickup_point DROP FOREIGN KEY FK_1467BEE88BAC62AF');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE pickup_point');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
