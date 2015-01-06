<?php
namespace Famelo\ADU\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Overview controller for the Famelo.ADU package 
 *
 * @Flow\Scope("singleton")
 */
class OverviewController extends \TYPO3\Flow\Mvc\Controller\ActionController {
	/**
	 * @var \Community\CacheExtensions\Services\CacheIdentityService
	 * @Flow\Inject
	 */
	protected $cacheIdentityService;

	/**
	 * @var \TYPO3\Flow\Cache\CacheManager
	 * @Flow\Inject
	 */
	protected $cacheManager;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		// $cache = $this->cacheManager->getCache('Community_CacheExtensions_EntityModificationTimestamps');
		// $i = '61024d47-8d25-4cd0-8e42-fef84c641e92';
		// $rating = $this->persistenceManager->getObjectByIdentifier($i, '\Famelo\ADU\Domain\Model\Rating');
		// $identifier = 'MyOutput' . $this->cacheIdentityService->getIdentifierByObject($rating);

		// $rating->setComment('Foo');
		// $this->persistenceManager->update($rating);
		// $this->persistenceManager->persistAll();

		// if (!$cache->has($identifier)) {
		// 	$cache->set($identifier, $rating->getComment());
		// }
		// echo $cache->get($identifier);
		// exit();

		// $identifier = $this->cacheIdentityService->getIdentifierByObject($rating);
		// var_dump($identifier);
	}

}

?>