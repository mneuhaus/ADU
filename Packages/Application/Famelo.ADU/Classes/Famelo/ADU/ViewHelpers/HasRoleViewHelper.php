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
class HasRoleViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractConditionViewHelper {
	/**
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Injects the security context
	 *
	 * @param \TYPO3\Flow\Security\Context $securityContext The security context
	 * @return void
	 */
	public function injectSecurityContext(\TYPO3\Flow\Security\Context $securityContext) {
		$this->securityContext = $securityContext;
	}

	/**
	 * renders <f:then> child if the role could be found in the security context,
	 * otherwise renders <f:else> child.
	 *
	 * @param string $role The role
	 * @return string the rendered string
	 * @api
	 */
	public function render($role) {
		$hasRole = FALSE;
		foreach ($this->securityContext->getAccount()->getRoles() as $userRole) {
			if ($role == strval($userRole)) {
				$hasRole = TRUE;
			}
		}

		if ($hasRole === TRUE) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}