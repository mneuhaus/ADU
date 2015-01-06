<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Contact
 *
 * @Flow\Entity
 */
class Contact {

	/**
	 * The firstname
	 * @var string
	 */
	protected $firstname;

	/**
	 * The lastname
	 * @var string
	 */
	protected $lastname;

	/**
	 * The phone
	 * @var string
	 */
	protected $phone;

	/**
	 * The e-mail
	 * @var string
	 */
	protected $email;

	public function __toString() {
		return $this->getFirstname() . ' ' . $this->getLastname();
	}

	public function getIdentity() {
		return $this->Persistence_Object_Identifier;
	}

	/**
	 * Get the Contact's firstname
	 *
	 * @return string The Contact's firstname
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Sets this Contact's firstname
	 *
	 * @param string $firstname The Contact's firstname
	 * @return void
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * Get the Contact's lastname
	 *
	 * @return string The Contact's lastname
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Sets this Contact's lastname
	 *
	 * @param string $lastname The Contact's lastname
	 * @return void
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * Get the Contact's phone
	 *
	 * @return string The Contact's phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Sets this Contact's phone
	 *
	 * @param string $phone The Contact's phone
	 * @return void
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * Get the Contact's email
	 *
	 * @return string The Contact's email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets this Contact's email
	 *
	 * @param string $email The Contact's email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

}
?>