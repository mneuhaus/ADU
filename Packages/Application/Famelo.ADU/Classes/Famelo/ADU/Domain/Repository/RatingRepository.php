<?php
namespace Famelo\ADU\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Ratings
 *
 * @Flow\Scope("singleton")
 */
class RatingRepository extends \TYPO3\Flow\Persistence\Repository {
	protected $defaultOrderings = array(
		'created' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING
	);

	/**
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Returns a query for objects of this repository
	 *
	 * @return \TYPO3\Flow\Persistence\QueryInterface
	 * @api
	 */
	public function createQuery($filtered = TRUE) {
		$query = parent::createQuery();
		if ($filtered === TRUE) {
			$startDate = new \DateTime('last Friday');
			$query->matching($query->greaterThan('created', $startDate));
			$query->setOrderings(array(
				'created' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING
			));

			$customers = $this->customerRepository->findAll()->toArray();

			$ratings = array();
			foreach ($query->execute() as $rating) {
				if (in_array($rating->getCustomer(), $customers)) {
					$identifier = $this->persistenceManager->getIdentifierByObject($rating->getCustomer());
					$ratings[] = $rating;
				}
			}
			$query = new \Famelo\ADU\Domain\ArrayQuery();
			$query->setArray($ratings);
			return $query;
		}
		return $query;
	}

}
?>