<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240428173621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_details (id INT AUTO_INCREMENT NOT NULL, order_a_id INT NOT NULL, article_id INT NOT NULL, size_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_845CA2C1762F694F (order_a_id), INDEX IDX_845CA2C17294869C (article_id), INDEX IDX_845CA2C1498DA827 (size_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1762F694F FOREIGN KEY (order_a_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C17294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1498DA827 FOREIGN KEY (size_id) REFERENCES sizes (id)');
        $this->addSql('ALTER TABLE article_order DROP FOREIGN KEY FK_829EE1897294869C');
        $this->addSql('ALTER TABLE article_order DROP FOREIGN KEY FK_829EE1898D9F6D38');
        $this->addSql('DROP TABLE article_order');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_order (article_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_829EE1897294869C (article_id), INDEX IDX_829EE1898D9F6D38 (order_id), PRIMARY KEY(article_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_order ADD CONSTRAINT FK_829EE1897294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_order ADD CONSTRAINT FK_829EE1898D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1762F694F');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C17294869C');
        $this->addSql('ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C1498DA827');
        $this->addSql('DROP TABLE order_details');
    }
}
