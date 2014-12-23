<?php
namespace Famelo\ADU\Tasks;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Expose".          *
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
class ReportingNotification implements \Famelo\Scheduler\Tasks\TaskInterface {
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
	protected $interval = '* * * * *';

	public function getInterval() {
		return $this->interval;
	}

	public function execute() {
		$customers = array();
		foreach ($this->customerRepository->findAll() as $customer) {
			if ($customer->getLatestRating() !== NULL && $customer->getLatestRating()->getLevel() > 1) {
				$customers[] = $customer;
			}
		}

		usort($customers, function($a, $b) {
			return $a->getLatestRating()->getLevel() < $b->getLatestRating()->getLevel();
		});

		$mail = new \Famelo\Messaging\Message();
		$mail
			->setFrom(array('no-reply@adu-kundenzufriedenheit.de' => 'ADU Kundenzufriedenheit'))
			->setSubject('ADU: Aktueller Stand der Selbsteinschätzung')
			->setMessage('Famelo.ADU:CustomerReport')
			->assign('customers', $customers);

		$mail->setTo(array('patrick.bremehr@neuland-medien.de'))->send();
		$mail->setTo(array('apocalip@gmail.com'))->send();

#		$mail->setTo(array('b.janz@adu-urban.de'))->send();
	}
}
?>