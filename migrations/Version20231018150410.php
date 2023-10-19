<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018150410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eth_price (id INT AUTO_INCREMENT NOT NULL, value DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nft_value DROP FOREIGN KEY FK_5A860697E813668D');
        $this->addSql('DROP INDEX IDX_5A860697E813668D ON nft_value');
        $this->addSql('ALTER TABLE nft_value CHANGE nft_id nft_model_id INT NOT NULL');
        $this->addSql('ALTER TABLE nft_value ADD CONSTRAINT FK_5A8606974CC9E6C9 FOREIGN KEY (nft_model_id) REFERENCES nft_model (id)');
        $this->addSql('CREATE INDEX IDX_5A8606974CC9E6C9 ON nft_value (nft_model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE eth_price');
        $this->addSql('ALTER TABLE nft_value DROP FOREIGN KEY FK_5A8606974CC9E6C9');
        $this->addSql('DROP INDEX IDX_5A8606974CC9E6C9 ON nft_value');
        $this->addSql('ALTER TABLE nft_value CHANGE nft_model_id nft_id INT NOT NULL');
        $this->addSql('ALTER TABLE nft_value ADD CONSTRAINT FK_5A860697E813668D FOREIGN KEY (nft_id) REFERENCES nft (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5A860697E813668D ON nft_value (nft_id)');
    }
}
