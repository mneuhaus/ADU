<?php
namespace Famelo\ADU\Domain\Dto;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use Doctrine\ORM\Mapping as ORM;
use Famelo\ADU\Domain\Model\Customer;
use TYPO3\Flow\Annotations as Flow;

/**
 *
 */
class CustomerSurveyYear {
	/**
	 * @var Customer
	 */
	protected $customer;

	/**
	 * @var integer
	 */
	protected $year;

	public function __construct($customer, $year) {
		$this->customer = $customer;
		if ($year === NULL) {
			$year = date('Y');
		}
		$this->year = $year;
	}

	/**
	 * @param Customer $customer
	 */
	public function setCustomer($customer) {
		$this->customer = $customer;
	}

	/**
	 * @return Customer
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * @param integer $year
	 */
	public function setYear($year) {
		$this->year = $year;
	}

	/**
	 * @return integer
	 */
	public function getYear() {
		return $this->year;
	}

	public function getSurveys() {
		$surveys = array();
		for ($i=1; $i <= 12; $i++) {
			$surveys[$i] = NULL;
		}
		foreach ($this->customer->getSurveys() as $survey) {
			$created = $survey->getCreated();
			if ($created->format('Y') !== $this->year) {
				continue;
			}
			$surveys[intval($created->format('m'))] = $survey;
		}
		return $surveys;
	}
}
?>