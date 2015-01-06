<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Category
 *
 * @Flow\Entity
 */
class Category {

	/**
	 * The name
	 * @var string
	 */
	protected $name;

	public function __toString() {
		return $this->getName();
	}

	/**
	 * Get the Category's name
	 *
	 * @return string The Category's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this Category's name
	 *
	 * @param string $name The Category's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

}
?>