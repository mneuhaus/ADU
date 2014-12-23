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
class ReportController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * The customerRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('thisWeek', intval(date('W')));
		$this->view->assign('lastWeek', intval(date('W')) - 1);
		$this->view->assign('twoWeeksAgo', intval(date('W')) - 2);
		$this->view->assign('reportService', new \Famelo\ADU\Services\ReportService());

		$customers = $this->customerRepository->findAll();
		$this->view->assign('customers', $customers);
	}

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function surveyAction() {
		$customers = $this->customerRepository->findAll();
		$this->view->assign('customers', $customers);
	}

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function selfEvaluationAction() {
		$this->view->assign('thisWeek', intval(date('W')));
		$this->view->assign('lastWeek', intval(date('W')) - 1);
		$this->view->assign('twoWeeksAgo', intval(date('W')) - 2);
		$this->view->assign('reportService', new \Famelo\ADU\Services\ReportService());
	}

	public function generateSelfEvaluationAction() {
		$document = new \Famelo\PDF\Document('Famelo.ADU:CustomerReport');
		$document->assign('customers', $this->customerRepository->findUnsatisfied());
		$document->download('ADU Bericht ' . date('d.m.Y') . '.pdf');
	}

	/**
	 * Index action
	 *
	 * @param \Famelo\ADU\Domain\Model\Customer $customer
	 * @return void
	 */
	public function customerAction($customer) {
		$this->view->assign('customer', $customer);
	}

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function overviewAction() {
		$customers = $this->customerRepository->findAll();
		$this->view->assign('customers', $customers);
	}
}

?>