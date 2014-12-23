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
class CustomerController extends \TYPO3\Flow\Mvc\Controller\ActionController {
	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {}

	/**
	 * Index action
	 *
	 * @param \Famelo\ADU\Domain\Model\Customer $customer
	 * @return void
	 */
	public function showAction($customer) {
		$this->view->assign('customer', $customer);
	}
}

?>