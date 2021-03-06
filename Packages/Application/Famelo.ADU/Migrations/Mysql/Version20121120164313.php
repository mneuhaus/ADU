<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121120164313 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
			// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE famelo_adu_domain_model_answer DROP FOREIGN KEY FK_A0955BF3AD5F9BFC");
		$this->addSql("ALTER TABLE famelo_adu_domain_model_answer ADD CONSTRAINT FK_A0955BF3AD5F9BFC FOREIGN KEY (survey) REFERENCES famelo_adu_domain_model_survey (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
			// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE famelo_adu_domain_model_answer DROP FOREIGN KEY FK_A0955BF3AD5F9BFC");
		$this->addSql("ALTER TABLE famelo_adu_domain_model_answer ADD CONSTRAINT FK_A0955BF3AD5F9BFC FOREIGN KEY (survey) REFERENCES famelo_adu_domain_model_survey (persistence_object_identifier) ON DELETE CASCADE");
	}
}

?>