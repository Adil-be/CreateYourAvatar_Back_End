<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112114601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft_model ADD nft_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nft_model ADD CONSTRAINT FK_90642C7A8197443 FOREIGN KEY (nft_image_id) REFERENCES nft_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90642C7A8197443 ON nft_model (nft_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nft_model DROP FOREIGN KEY FK_90642C7A8197443');
        $this->addSql('DROP INDEX UNIQ_90642C7A8197443 ON nft_model');
        $this->addSql('ALTER TABLE nft_model DROP nft_image_id');
    }
}
