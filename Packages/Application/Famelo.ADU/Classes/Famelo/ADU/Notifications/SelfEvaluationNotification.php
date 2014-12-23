<?php
namespace Famelo\ADU\Notifications;

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
class SelfEvaluationNotification implements NotificationInterface {
	/**
	 * The customerRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	public function getNotifications() {
		$customer = $this->customerRepository->findAll()->getFirst();
		if (!is_object($customer) || !is_object($customer->getLatestRating())) {
			return array();
		}
		$lastRating = $customer->getLatestRating()->getCreated();

		$now = new \DateTime();

		$deadline = '6';
		$notice = '12';
		$nextSaturday = new \DateTime('next friday');

		$diff = $lastRating->diff($now);
		$hoursSinceLastRating = ($diff->i / 60) + $diff->h + ($diff->days * 24);
		if ($diff->invert == 1) {
			$hoursSinceLastRating = $hoursSinceLastRating * -1;
		}

		$diff = $now->diff($nextSaturday);
		$remaining = ($diff->i / 60) + $diff->h + ($diff->days * 24);
		if ($diff->invert == 1) {
			$remaining = $remaining * -1;
		}

		if ($hoursSinceLastRating < $notice) {
			return array();
		}

		if ($hoursSinceLastRating > (7 * 24) || $remaining <= $deadline) {
			return array(
				array(
					'text' => 'Selbsteinschätzung durchführen',
					'class' => 'important',
					'action' => array('action' => 'happiness', 'controller' => 'Survey', 'package' => 'Famelo.ADU')
				),
			);
		} elseif ($remaining < $notice) {
			return array(
				array(
					'text' => 'Selbsteinschätzung durchführen',
					'class' => 'warning',
					'action' => array('action' => 'happiness', 'controller' => 'Survey', 'package' => 'Famelo.ADU')
				),
			);
		}
		return array();
	}
}
?>