<?php
namespace Famelo\ADU\Domain\Repository;

/*                                                                        *
 * This script belongs to the FLOW3 package "Brain".                      *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Users
 *
 * @Flow\Scope("singleton")
 */
class UserRepository extends \TYPO3\Flow\Persistence\Repository {
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

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
	public function createQuery() {
		$query = parent::createQuery();
		if (PHP_SAPI === 'cli') {
			// Full Access
		} elseif ($this->securityContext->hasRole('Administrator')) {
			// Full Access
		} elseif ($this->securityContext->hasRole('Niederlassungsleiter')) {
			$query->matching($query->equals('branch', $this->securityContext->getParty()->getBranch()));
		}
		return $query;
	}

	public function findOneByUsername($username) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'ADUProvider');
		return $account->getParty();
	}


}
?>
