<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20141216154913 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
			// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE famelo_adu_domain_model_answer ADD action VARCHAR(255) NOT NULL");
		$this->addSql("ALTER TABLE famelo_adu_domain_model_customer CHANGE type type VARCHAR(255) NOT NULL");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
			// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");

		$this->addSql("ALTER TABLE famelo_adu_domain_model_answer DROP action");
		$this->addSql("ALTER TABLE famelo_adu_domain_model_customer CHANGE type type VARCHAR(1) DEFAULT NULL");
	}
}

?>