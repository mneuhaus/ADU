<?php
namespace Famelo\ADU\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Surveys
 *
 * @Flow\Scope("singleton")
 */
class SurveyRepository extends \TYPO3\Flow\Persistence\Repository {
	/**
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	public function getSurveyResultsByMonth() {
		$results = array();

			// Collect results
		foreach ($this->findAll() as $survey) {
			$month = $survey->getCreated()->format('n');
			if (!isset($results[$month])) {
				$results[$month] = array(
					'label' => $survey->getCreated()->format('m.Y'),
					'values' => array()
				);
			}
			$results[$month]['values'][] = $survey->getResult();
		}
		sort($results);

		foreach ($results as $month => $data) {
			$results[$month]['value'] = array_sum($data['values']) / count($data['values']);
		}

		return $results;
	}

	/**
	 * Returns a query for objects of this repository
	 *
	 * @return \TYPO3\Flow\Persistence\QueryInterface
	 * @api
	 */
	public function createQuery() {
		$query = parent::createQuery();
		// $startDate = new \DateTime('last friday');
		// $query->matching($query->greaterThan('created', $startDate));
		$query->setOrderings(array(
			'created' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING
		));

		$customers = $this->customerRepository->findAll()->toArray();

		$surveys = array();
		foreach ($query->execute() as $survey) {
			if (in_array($survey->getCustomer(), $customers)) {
				$surveys[] = $survey;
			}
		}

		$query = new \Famelo\ADU\Domain\ArrayQuery();
		$query->setArray($surveys);
		return $query;
	}
}
?>