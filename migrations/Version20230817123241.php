<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817123241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft_model DROP FOREIGN KEY FK_90642C7A41E2658F');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_nft_model (category_id INT NOT NULL, nft_model_id INT NOT NULL, INDEX IDX_5704FE4A12469DE2 (category_id), INDEX IDX_5704FE4A4CC9E6C9 (nft_model_id), PRIMARY KEY(category_id, nft_model_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_nft_model ADD CONSTRAINT FK_5704FE4A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_nft_model ADD CONSTRAINT FK_5704FE4A4CC9E6C9 FOREIGN KEY (nft_model_id) REFERENCES nft_model (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nft_category DROP FOREIGN KEY FK_33F048EF727ACA70');
        $this->addSql('DROP TABLE nft_category');
        $this->addSql('ALTER TABLE nft_image DROP FOREIGN KEY FK_82CC5AFCE813668D');
        $this->addSql('DROP INDEX IDX_82CC5AFCE813668D ON nft_image');
        $this->addSql('ALTER TABLE nft_image CHANGE nft_id nft_model_id INT NOT NULL');
        $this->addSql('ALTER TABLE nft_image ADD CONSTRAINT FK_82CC5AFC4CC9E6C9 FOREIGN KEY (nft_model_id) REFERENCES nft_model (id)');
        $this->addSql('CREATE INDEX IDX_82CC5AFC4CC9E6C9 ON nft_image (nft_model_id)');
        $this->addSql('DROP INDEX IDX_90642C7A41E2658F ON nft_model');
        $this->addSql('ALTER TABLE nft_model DROP nft_category_id, CHANGE initial_price initial_price DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE nft_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, image_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_33F048EF727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE nft_category ADD CONSTRAINT FK_33F048EF727ACA70 FOREIGN KEY (parent_id) REFERENCES nft_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category_nft_model DROP FOREIGN KEY FK_5704FE4A12469DE2');
        $this->addSql('ALTER TABLE category_nft_model DROP FOREIGN KEY FK_5704FE4A4CC9E6C9');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_nft_model');
        $this->addSql('ALTER TABLE nft_image DROP FOREIGN KEY FK_82CC5AFC4CC9E6C9');
        $this->addSql('DROP INDEX IDX_82CC5AFC4CC9E6C9 ON nft_image');
        $this->addSql('ALTER TABLE nft_image CHANGE nft_model_id nft_id INT NOT NULL');
        $this->addSql('ALTER TABLE nft_image ADD CONSTRAINT FK_82CC5AFCE813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_82CC5AFCE813668D ON nft_image (nft_id)');
        $this->addSql('ALTER TABLE nft_model ADD nft_category_id INT DEFAULT NULL, CHANGE initial_price initial_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE nft_model ADD CONSTRAINT FK_90642C7A41E2658F FOREIGN KEY (nft_category_id) REFERENCES nft_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_90642C7A41E2658F ON nft_model (nft_category_id)');
    }
}
