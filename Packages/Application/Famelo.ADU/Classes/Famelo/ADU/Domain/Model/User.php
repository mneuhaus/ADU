<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the FLOW3 package "Party".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * A person
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class User extends \TYPO3\Party\Domain\Model\Person {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Flow\Security\Account>
	 * @ORM\OneToMany(mappedBy="party", cascade={"persist", "remove"})
	 * @Flow\Lazy
	 */
	protected $accounts;

	/**
	 * The phone
	 * @var string
	 */
	protected $phone = '';

	/**
	 * The origin
	 * @var string
	 */
	protected $origin = 'manual';

	/**
	 * The mobile
	 * @var string
	 */
	protected $mobile = '';

	/**
	 * The email
	 * @var string
	 */
	protected $email;

	/**
	 * The group
	 * @var \Famelo\ADU\Domain\Model\Branch
	 * @ORM\ManyToOne(inversedBy="users", cascade={"persist", "detach"})
	 */
	protected $branch;

	/**
	 * The role
	 * @var string
	 */
	protected $role = '';

	/**
	 * The surveys
	 *
	 * @var \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Customer>
	 * @ORM\OneToMany(mappedBy="consultant", cascade={"persist"})
	 * @Flow\Lazy
	 */
	protected $customers;

	public function __construct() {
		if ($this->accounts == NULL) {
			parent::__construct();
			$account = new \TYPO3\Flow\Security\Account();
			$account->setAuthenticationProviderName('ADUProvider');
			$this->addAccount($account);
		} else {
			parent::__construct();
		}
	}

	/**
	 * @ORM\PreRemove
	 */
	public function preRemove() {
		foreach ($this->customers as $customer) {
			$customer->setConsultant(NULL);
		}
		$this->customers = array();
	}

	/**
	 * @ORM\PrePersist
	 */
	public function updateAccounts() {
		foreach ($this->accounts as $account) {
			$account->setParty($this);
		}
	}

	public function __toString() {
		return $this->name->__toString();
	}

	public function getUsername() {
		return $this->getAccounts()->first()->getAccountIdentifier();
	}

	/**
	 * Returns the accounts of this party
	 *
	 * @param \Doctrine\Common\Collections\Collection $accounts
	 */
	public function setAccounts($accounts) {
		$this->accounts = $accounts;
	}

	/**
	 * Get the User's firstname
	 *
	 * @return string The User's firstname
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Sets this User's firstname
	 *
	 * @param string $firstname The User's firstname
	 * @return void
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * Get the User's lastname
	 *
	 * @return string The User's lastname
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Sets this User's lastname
	 *
	 * @param string $lastname The User's lastname
	 * @return void
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * Get the User's phone
	 *
	 * @return string The User's phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Sets this User's phone
	 *
	 * @param string $phone The User's phone
	 * @return void
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * Get the User's mobile
	 *
	 * @return string The User's mobile
	 */
	public function getMobile() {
		return $this->mobile;
	}

	/**
	 * Sets this User's mobile
	 *
	 * @param string $mobile The User's mobile
	 * @return void
	 */
	public function setMobile($mobile) {
		$this->mobile = $mobile;
	}

	/**
	 * Get the User's email
	 *
	 * @return string The User's email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets this User's email
	 *
	 * @param string $email The User's email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Get the User's branch
	 *
	 * @return \Famelo\ADU\Domain\Model\Branch The User's branch
	 */
	public function getBranch() {
		// if ($this->branch == NULL && is_object($this->getCustomers()) && is_object($this->getCustomers()->first())) {
		// 	return $this->getCustomers()->first()->getBranch();
		// }
		return $this->branch;
	}

	/**
	 * Sets this User's branch
	 *
	 * @param \Famelo\ADU\Domain\Model\Branch $branch The User's branch
	 * @return void
	 */
	public function setBranch($branch) {
		$this->branch = $branch;
	}

	/**
	 * Get the User's role
	 *
	 * @return string The User's role
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * Sets this User's role
	 *
	 * @param string $role The User's role
	 * @return void
	 */
	public function setRole($role) {
		$this->role = $role;
	}

	/**
	 * Get the User's customers
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Customer>
	 */
	public function getCustomers() {
		return $this->customers;
	}

	/**
	 * Sets this User's customers
	 *
	 * @param \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Customer> $customers The User's customers
	 * @return void
	 */
	public function setCustomers($customers) {
		foreach ($customers as $customer) {
			$customer->setConsultant($this);
		}
		$this->customers = $customers;
	}


	public function getDidNotSelfEvaluate() {
		$customer = $this->getCustomers()->first();
		if (is_object($customer)) {
			if (!is_object($customer->getLatestRating())) {
				return array();
			}
			$lastRating = $customer->getLatestRating()->getCreated();

				// Check if the user rated customers since last Wednesday (0 0 * * 3)
			$interval = \Cron\CronExpression::factory('0 0 * * 3');
			return $interval->getPreviousRunDate() > $lastRating;
		}
	}

	/**
	 * @param string $origin
	 */
	public function setOrigin($origin) {
		$this->origin = $origin;
	}

	/**
	 * @return string
	 */
	public function getOrigin() {
		return $this->origin;
	}
}

?>