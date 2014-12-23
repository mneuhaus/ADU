<?php
namespace Famelo\ADU\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Customers
 *
 * @Flow\Scope("singleton")
 */
class CustomerRepository extends \TYPO3\Flow\Persistence\Repository {
	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * Returns a query for objects of this repository
	 *
	 * @return \TYPO3\Flow\Persistence\QueryInterface
	 * @api
	 */
	public function createQuery($filtered = TRUE) {
		$query = parent::createQuery();
		if ($filtered === TRUE) {
			$threshold = new \DateTime('-30d');
			$query->matching($query->logicalOr(
				$query->greaterThan('termination', $threshold),
				$query->equals('termination', NULL)
			));
			if (PHP_SAPI === 'cli') {
				// Full Access
			} elseif ($this->securityContext->hasRole('Administrator')) {
				// Full Access
				
			} elseif ($this->securityContext->hasRole('Innendienst')) {
				// Full Access
				
			} elseif ($this->securityContext->hasRole('Niederlassungsleiter')) {
				$query->matching(
					$query->logicalAnd(
						$query->getConstraint(),
						$query->equals('branch', $this->securityContext->getParty()->getBranch())
					)
				);
			} else {
				$query->matching(
					$query->logicalAnd(
						$query->getConstraint(),
						$query->equals('consultant', $this->securityContext->getParty())
					)
				);
			}
		}
		return $query;
	}

	/**
	 * findUnsatisfied
	 *
	 * @param string $search (NOT PART OF THE SPECIFICATION)
	 * @param \Famelo\ADU\Domain\Model\User $consultantObj (INTEGRATED)
	 * @param \Famelo\ADU\Domain\Model\Branch $branchObj (INTEGRATED)
	 * @param string $sort (INTEGRATED)
	 * @param string $cycle (INTEGRATED)
     * @param boolean $filtered
	 */
	public function findUnsatisfied($search='', $customerObj=NULL, $consultantObj=NULL, $branchObj=NULL, $sort='', $cycle='', $filtered=true) {
		$query = $this->createQuery($filtered);
		
		if ($sort=='sorting') {
			$query->setOrderings(array(
				'selfEvaluationResult' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING
			));
		} else if ($sort=='name') {
			$query->setOrderings(array(
				'name' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING
			));			
		}

		if ($branchObj != NULL) {
			$query->matching(
				$query->logicalAnd(
					$query->getConstraint(),
					$query->equals('branch', $branchObj)
				)
			);
		}

		if ($consultantObj != NULL) {
			$query->matching(
				$query->logicalAnd(
					$query->getConstraint(),
					$query->equals('consultant', $consultantObj)
				)
			);
		}


		$customers = array();
		$customersCollection = array();
		
		// sort it for pdf
		foreach ($query->execute() as $customer) {
			if ($customerObj === NULL || $customer->getIdentity() === $customerObj->getIdentity()) { // filter $customerObj
                $id = $customer->getName().microtime(true);
			    $customers[$id]       = $customer->getRatingSum();
			    $customersCollection[$id] = $customer;
            }
		}
		if ($sort=='sorting') arsort($customers);
		
		
		$customersFinish = array();
		foreach ($customers as $customerId => $ratingSum) {
			$customer = $customersCollection[$customerId]; // set back customer object

            if ($filtered === true) {

			if ($ratingSum > 20 || $customer->getIsTerminated() || $customer->getIsNew() || $customer->getIsTender()) {
				if ($customer->getIsTerminated() || $customer->getIsNew() || $customer->getIsTender()) {
					$customersFinish[$customer->getName()] = $customer;
				} else {
					$customersFinish[] = $customer;
				}
			}
            } else {
                $customersFinish[] = $customer;
            }
		}

		
		return $customersFinish;
	}

}
?>
