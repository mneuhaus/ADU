<?php
namespace Famelo\ADU\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Survey controller for the Famelo.ADU package 
 *
 * @Flow\Scope("singleton")
 */
class ExportController extends \TYPO3\Flow\Mvc\Controller\ActionController {
	/**
	 * @param \Famelo\ADU\Domain\Model\Survey $survey
	 * @return void
	 */
	public function surveyAction($survey) {
		$document = new \Famelo\PDF\Document('Famelo.ADU:SurveySummary');
		$document->assign('survey', $survey);
		$document->download('ADU Bewertung ' . $survey->getCreated()->format('d.m.Y') . '.pdf');
	}
}

?>