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
	 * The branchRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\BranchRepository
	 * @Flow\Inject
	 */
	protected $branchRepository;

	/**
	 * The userRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\UserRepository
	 * @Flow\Inject
	 */
	protected $userRepository;


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

	/**
	 * generateSelfEvaluation action
	 *
	 * @param string $search
     * @param string $customerId
	 * @param string $consultant
	 * @param string $branch
	 * @param string $sort
	 * @param string $cycle
     * @param string $showComments
	 * @return void
	 */
	public function generateSelfEvaluationAction($search='', $customerId='', $consultant='', $branch='', $sort='', $cycle='', $showComments) {
		$document = new \Famelo\PDF\Document('Famelo.ADU:CustomerReport');

		$branchObj     = NULL;
        $customerObj   = NULL;
		$consultantObj = NULL;

		if ($branch != '')     $branchObj     = $this->branchRepository->findOneByName($branch);
		if ($customerId != '') $customerObj   = $this->customerRepository->findByIdentifier($customerId);
        if ($consultant != '') $consultantObj = $this->userRepository->findOneByUsername($consultant);

		$document->assign('cycle', $cycle);
        $document->assign('yearReporting', ($cycle==='currentYear' || $cycle==='lastYear' || $cycle==='-2years'));
        $document->assign('showComments', $showComments);

        $filtered=false;
        $objects = $this->customerRepository->findUnsatisfied($search, $customerObj, $consultantObj, $branchObj, $sort, $cycle, $filtered);
        $customers = array();
        foreach ($objects as $object) {
        	if (isset($customers[$object->getName()])) {
        		continue;
        	}
        	$customers[$object->getName()] = $object;
        }
        $document->assign('objects', $objects);
        $document->assign('customers', $customers);

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
