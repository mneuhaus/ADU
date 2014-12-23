<?php
namespace Famelo\ADU\Tasks;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Expose".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Manipulate the context variable "objects", which we expect to be a QueryResultInterface;
 * taking the "page" context variable into account (on which page we are currently).
 *
 */
class GrumpyCustomerNotification implements \Famelo\Scheduler\Tasks\TaskInterface {
	/**
	 * The customerRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	/**
	 * Run every Friday at 12:00
	 *
	 * @var string
	 */
	protected $interval = '0 9 * * 1';

	public function getInterval() {
		return $this->interval;
	}

	public function execute() {
		return;
		$mail = new \Famelo\Messaging\Message();

		$customers = array();
		foreach ($this->customerRepository->findAll() as $customer) {
			if (!$customer->isSatisfied()) {
				$customers[] = $customer;
			}
		}

		$mail
			->setFrom(array('apocalip@gmail.com' => 'Famelo.PackageCatalog'))
			->setTo(array('apocalip@gmail.com'))
			->setSubject('ADU: Übersicht über unzufriedene Kunden')
			->setMessage('Famelo.ADU:GrumpyCustomer')
			->assign('customers', $customers)
			->send();
	}
}
?>