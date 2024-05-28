<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428160338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_client DROP FOREIGN KEY FK_AD8393D57294869C');
        $this->addSql('ALTER TABLE article_client DROP FOREIGN KEY FK_AD8393D519EB6921');
        $this->addSql('DROP TABLE article_client');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_client (article_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_AD8393D519EB6921 (client_id), INDEX IDX_AD8393D57294869C (article_id), PRIMARY KEY(article_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_client ADD CONSTRAINT FK_AD8393D57294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_client ADD CONSTRAINT FK_AD8393D519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
    }
}
