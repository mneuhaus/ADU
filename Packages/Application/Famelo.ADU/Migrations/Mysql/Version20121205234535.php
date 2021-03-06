<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121205234535 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
			// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE famelo_adu_domain_model_rating ADD customer VARCHAR(40) DEFAULT NULL");
		$this->addSql("ALTER TABLE famelo_adu_domain_model_rating ADD CONSTRAINT FK_A2C137F481398E09 FOREIGN KEY (customer) REFERENCES famelo_adu_domain_model_customer (persistence_object_identifier)");
		$this->addSql("CREATE INDEX IDX_A2C137F481398E09 ON famelo_adu_domain_model_rating (customer)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
			// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE famelo_adu_domain_model_rating DROP FOREIGN KEY FK_A2C137F481398E09");
		$this->addSql("DROP INDEX IDX_A2C137F481398E09 ON famelo_adu_domain_model_rating");
		$this->addSql("ALTER TABLE famelo_adu_domain_model_rating DROP customer");
	}
}

?>