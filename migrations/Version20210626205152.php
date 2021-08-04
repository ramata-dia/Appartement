<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210626205152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP introduction');
        $this->addSql('ALTER TABLE comment ADD annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_9474526C8805AB2F ON comment (annonce_id)');
        $this->addSql('ALTER TABLE image ADD anounce_id INT NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE imageurl image_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F87016831 FOREIGN KEY (anounce_id) REFERENCES annonce (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F87016831 ON image (anounce_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD introduction LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8805AB2F');
        $this->addSql('DROP INDEX IDX_9474526C8805AB2F ON comment');
        $this->addSql('ALTER TABLE comment DROP annonce_id');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F87016831');
        $this->addSql('DROP INDEX IDX_C53D045F87016831 ON image');
        $this->addSql('ALTER TABLE image DROP anounce_id, CHANGE description description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_url imageurl VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
