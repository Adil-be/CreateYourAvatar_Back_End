<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230816092350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nft (id INT AUTO_INCREMENT NOT NULL, nft_model_id INT DEFAULT NULL, user_id INT DEFAULT NULL, buying_price DOUBLE PRECISION NOT NULL, token VARCHAR(255) NOT NULL, in_sale TINYINT(1) NOT NULL, purchase_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D9C7463C4CC9E6C9 (nft_model_id), INDEX IDX_D9C7463CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_33F048EF727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_image (id INT AUTO_INCREMENT NOT NULL, nft_id INT NOT NULL, path VARCHAR(255) NOT NULL, size INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, INDEX IDX_82CC5AFCE813668D (nft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_model (id INT AUTO_INCREMENT NOT NULL, nft_collection_id INT DEFAULT NULL, nft_category_id INT DEFAULT NULL, initial_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', quantity INT NOT NULL, description VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_90642C7A327C6A9D (nft_collection_id), INDEX IDX_90642C7A41E2658F (nft_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nft_value (id INT AUTO_INCREMENT NOT NULL, nft_id INT NOT NULL, value_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', value DOUBLE PRECISION NOT NULL, INDEX IDX_5A860697E813668D (nft_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_image (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, path VARCHAR(255) NOT NULL, size INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_27FFFF07A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463C4CC9E6C9 FOREIGN KEY (nft_model_id) REFERENCES nft_model (id)');
        $this->addSql('ALTER TABLE nft ADD CONSTRAINT FK_D9C7463CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE nft_category ADD CONSTRAINT FK_33F048EF727ACA70 FOREIGN KEY (parent_id) REFERENCES nft_category (id)');
        $this->addSql('ALTER TABLE nft_image ADD CONSTRAINT FK_82CC5AFCE813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE nft_model ADD CONSTRAINT FK_90642C7A327C6A9D FOREIGN KEY (nft_collection_id) REFERENCES nft_collection (id)');
        $this->addSql('ALTER TABLE nft_model ADD CONSTRAINT FK_90642C7A41E2658F FOREIGN KEY (nft_category_id) REFERENCES nft_category (id)');
        $this->addSql('ALTER TABLE nft_value ADD CONSTRAINT FK_5A860697E813668D FOREIGN KEY (nft_id) REFERENCES nft (id)');
        $this->addSql('ALTER TABLE user_image ADD CONSTRAINT FK_27FFFF07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463C4CC9E6C9');
        $this->addSql('ALTER TABLE nft DROP FOREIGN KEY FK_D9C7463CA76ED395');
        $this->addSql('ALTER TABLE nft_category DROP FOREIGN KEY FK_33F048EF727ACA70');
        $this->addSql('ALTER TABLE nft_image DROP FOREIGN KEY FK_82CC5AFCE813668D');
        $this->addSql('ALTER TABLE nft_model DROP FOREIGN KEY FK_90642C7A327C6A9D');
        $this->addSql('ALTER TABLE nft_model DROP FOREIGN KEY FK_90642C7A41E2658F');
        $this->addSql('ALTER TABLE nft_value DROP FOREIGN KEY FK_5A860697E813668D');
        $this->addSql('ALTER TABLE user_image DROP FOREIGN KEY FK_27FFFF07A76ED395');
        $this->addSql('DROP TABLE nft');
        $this->addSql('DROP TABLE nft_category');
        $this->addSql('DROP TABLE nft_collection');
        $this->addSql('DROP TABLE nft_image');
        $this->addSql('DROP TABLE nft_model');
        $this->addSql('DROP TABLE nft_value');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_image');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
