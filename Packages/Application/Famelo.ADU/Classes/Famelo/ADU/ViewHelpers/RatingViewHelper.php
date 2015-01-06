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
class RatingViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * The reportService
	 *
	 * @var \Famelo\ADU\Services\ReportService
	 * @Flow\Inject
	 */
	protected $reportService;

	/**
	 * @param object $customer
	 * @param integer $weeks
	 * @return string Rendered string
	 */
	public function render($customer, $weeks = 3) {
		$week = $this->reportService->getDateTime()->format('W');
		$output = '';
		while ($weeks > 0) {
			$this->templateVariableContainer->add('rating', $this->reportService->getRatingForWeek($customer, $week));
			$output .= $this->renderChildren();
			$this->templateVariableContainer->remove('rating');
			$weeks--;
			$week--;
		}
		return $output;
	}
}

?>