<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151018113251 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD externalId BIGINT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A770AC6E ON users (externalId)');
        
        $this->addSql('DROP INDEX UNIQ_1483A5E9A0D96FBF ON users');
        $this->addSql('ALTER TABLE users CHANGE email_canonical emailCanonical VARCHAR(255) NOT NULL');

        $this->addSql('ALTER TABLE users CHANGE email email VARCHAR(255) DEFAULT NULL');
        
        $this->addSql('ALTER TABLE users CHANGE emailCanonical emailCanonical VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users CHANGE emailCanonical emailCanonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        
        $this->addSql('ALTER TABLE users CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');

        $this->addSql('ALTER TABLE users CHANGE emailcanonical email_canonical VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A0D96FBF ON users (email_canonical)');
        
        $this->addSql('DROP INDEX UNIQ_1483A5E9A770AC6E ON users');
        $this->addSql('ALTER TABLE users DROP externalId');
    }
}
