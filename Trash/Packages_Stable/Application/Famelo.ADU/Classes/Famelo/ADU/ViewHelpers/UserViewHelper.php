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
class UserViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {
	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @param string $as
	 * @return string Rendered string
	 */
	public function render($as = 'user') {
		$this->templateVariableContainer->add($as, $this->securityContext->getAccount());
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $output;
	}
}

?>