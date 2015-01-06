<?php
namespace Famelo\ADU\Services;

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
class ReportService {
	/**
	 * The customerRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	public function getBranchPieCharts() {
		$customers = $this->customerRepository->findAll();
		$branches = array(
			'Gesamt' => array(
				'red' => 0,
				'orange' => 0,
				'yellow' => 0,
				'green' => 0
			)
		);
		foreach ($customers as $customer) {
			$survey = $customer->getLatestSurvey();
			if ($survey instanceof \Famelo\ADU\Domain\Model\Survey) {
				$branch = $customer->getBranch();
				if (!isset($branches[$branch->getName()])) {
					$branches[$branch->getName()] = array(
						'red' => 0,
						'orange' => 0,
						'yellow' => 0,
						'green' => 0
					);
				}
				$branches[$branch->getName()][$survey->getResultColor()]++;
				$branches['Gesamt'][$survey->getResultColor()]++;
			}
		}

		$charts = array();
		foreach ($branches as $branch => $data) {
			$piChart = new \gchart\gPieChart(200, 200);
			$piChart->setTitle($branch . ' (' . array_sum($data) . ')');
			$piChart->addDataSet(array_values($data));
			$precentages = array();
			if (array_sum($data) > 0) {
				foreach ($data as $key => $value) {
					$precentages[$key] = intval( ( $value / array_sum($data) ) * 100) . '%';
				}
				$piChart->setLegend($precentages);
				$piChart->setColors(array('df001a', 'f29400', 'ffed00', '009036'));
				$charts[] = str_replace('&amp;', '&', $piChart->getUrl());
			}
		}
		return $charts;
	}
}
?>