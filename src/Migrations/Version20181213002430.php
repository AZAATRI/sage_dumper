<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181213002430 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commercial ADD user_id INT NOT NULL, DROP username, DROP password, DROP role_matcher');
        $this->addSql('ALTER TABLE commercial ADD CONSTRAINT FK_7653F3AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7653F3AEA76ED395 ON commercial (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commercial DROP FOREIGN KEY FK_7653F3AEA76ED395');
        $this->addSql('DROP INDEX UNIQ_7653F3AEA76ED395 ON commercial');
        $this->addSql('ALTER TABLE commercial ADD username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD role_matcher VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP user_id');
    }
}
