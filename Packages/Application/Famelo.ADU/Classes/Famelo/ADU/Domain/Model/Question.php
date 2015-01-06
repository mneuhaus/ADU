<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Question
 *
 * @Flow\Entity
 */
class Question {

	/**
	 * The body
	 * @var string
	 */
	protected $body;

	/**
	 * The weight
	 * @var float
	 */
	protected $weight;

	/**
	 * The type
	 * @var string
	 */
	protected $type;

	public function __toString() {
		return $this->getBody();
	}

	/**
	 * Get the Question's body
	 *
	 * @return string The Question's body
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Sets this Question's body
	 *
	 * @param string $body The Question's body
	 * @return void
	 */
	public function setBody($body) {
		$this->body = $body;
	}

	/**
	 * Get the Question's weight
	 *
	 * @return float The Question's weight
	 */
	public function getWeight() {
		return $this->weight;
	}

	/**
	 * Sets this Question's weight
	 *
	 * @param float $weight The Question's weight
	 * @return void
	 */
	public function setWeight($weight) {
		$this->weight = $weight;
	}

	/**
	 * Get the Question's type
	 *
	 * @return string The Question's type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets this Question's type
	 *
	 * @param string $type The Question's type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

}
?>