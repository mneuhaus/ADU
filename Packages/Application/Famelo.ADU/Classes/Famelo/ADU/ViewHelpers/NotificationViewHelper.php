<?php
namespace Famelo\ADU\ViewHelpers;

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
 */
class NotificationViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @param string $as
	 * @return string Rendered string
	 */
	public function render() {
		$notificationClasses = $this->reflectionService->getAllImplementationClassNamesForInterface('\Famelo\ADU\Notifications\NotificationInterface');
		$notifications = array();
		foreach ($notificationClasses as $notificationClass) {
			$notification = new $notificationClass();
			$notifications = array_merge($notifications, $notification->getNotifications());
		}

		$this->templateVariableContainer->add('notifications', $notifications);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove('notifications');
		return $output;
	}
}

?>