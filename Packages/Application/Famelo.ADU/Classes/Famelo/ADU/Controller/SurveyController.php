<?php
namespace Famelo\ADU\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use Famelo\ADU\Domain\Dto\CustomerSurveyYear;
use TYPO3\Flow\Annotations as Flow;

/**
 * Survey controller for the Famelo.ADU package
 *
 * @Flow\Scope("singleton")
 */
class SurveyController extends \TYPO3\Flow\Mvc\Controller\ActionController {
	/**
	 * The securityContext
	 *
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * The customerRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 * @Flow\Inject
	 */
	protected $customerRepository;

	/**
	 * The customerRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\UserRepository
	 * @Flow\Inject
	 */
	protected $userRepository;

	/**
	 * The branchRepository
	 *
	 * @var \Famelo\ADU\Domain\Repository\BranchRepository
	 * @Flow\Inject
	 */
	protected $branchRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {}


    /**
     * Review action
     *
     * @param string $year
     * @return void
     */
    public function reviewAction($year = NULL) {
        $query = $this->customerRepository->createQuery(TRUE, TRUE);
        $this->initializeViewVariables($query, $this->view, $year);
		$this->view->assign('months', $this->getMonths());
		$this->view->assign('years', $this->getYears());
		$this->view->assign('currentYear', $year);

        // switch ($cycle) {
        //     case 'currentYearPart1':
        //     case 'lastYearPart1':
        //     case 'last2YearsPart1':
        //         $startWeek = 1; $endWeek = 13;
        //         break;

        //     case 'currentYearPart2':
        //     case 'lastYearPart2':
        //     case 'last2YearsPart2':
        //         $startWeek = 14; $endWeek = 26;
        //         break;

        //     case 'currentYearPart3':
        //     case 'lastYearPart3':
        //     case 'last2YearsPart3':
        //         $startWeek = 27; $endWeek = 39;
        //         break;

        //     case 'currentYearPart4':
        //     case 'lastYearPart4':
        //     case 'last2YearsPart4':
        //         $startWeek = 40; $endWeek = 52;
        //         break;

        //     default:
        //         $startWeek = 1; $endWeek = 13;
        // }

        // if (strpos($cycle,'currentYear')!==false) $year = 'current';
        // else if (strpos($cycle,'lastYear')!==false) $year = 'last';
        // else if (strpos($cycle,'last2Years')!==false) $year = 'last2';
        // else $year='current';


        // $query = $this->customerRepository->createQuery();
        // // $query->setLimit('20');
        // $this->view->assign('reportService', new \Famelo\ADU\Services\ReportService());
        // $this->view->assign('customers', $query->execute());
        // $this->view->assign('startWeek', $startWeek);
        // $this->view->assign('endWeek', $endWeek);
        // $this->view->assign('year', $year);
        // $this->view->assign('cycle', $cycle);

    }

    /**
     * Review action
     *
     * @param string $year
     * @param string $consultant
     * @param string $branch
     * @return void
     */
    public function reviewPdfAction($year = NULL, $consultant = NULL, $branch = NULL) {
        $query = $this->customerRepository->createQuery();
		$constraints = array($query->getConstraint());
        if (!empty($consultant)) {
        	$consultant = $this->userRepository->findOneByUsername($consultant);
        	$constraints[] = $query->equals('consultant', $consultant);
        }
        if (!empty($branch)) {
        	$branch = $this->branchRepository->findOneByName($branch);
        	$constraints[] = $query->equals('branch', $branch);
        }

        if (count($constraints) > 0) {
        	$query->matching($query->logicalAnd($constraints));
        }

        $document = new \Famelo\PDF\Document('Famelo.ADU:Review');

        $this->initializeViewVariables($query, $document, $year);
		$document->assign('months', $this->getMonths());
		$document->assign('years', $this->getYears());
		$document->assign('currentYear', $year);
		$document->download('ADU Überprüfung ' . date('d.m.Y') . '.pdf');
    }

    public function getMonths() {
    	return array(
    		1 => 'Jan',
    		2 => 'Feb',
    		3 => 'Mär',
    		4 => 'Apr',
    		5 => 'Mai',
    		6 => 'Jul',
    		7 => 'Jun',
    		8 => 'Aug',
    		9 => 'Sep',
    		10 => 'Okt',
    		11 => 'Nov',
    		12 => 'Dez'
    	);
    }

    public function getYears() {
    	$thisYear = date('Y');
        $years = array(
        	$thisYear => 'aktuelles Jahr (' . $thisYear . ')',
        	($thisYear - 1) => 'letztes Jahr (' . ($thisYear - 1) . ')',
        	($thisYear - 2) => 'vorletztes Jahr (' . ($thisYear - 2) . ')'
        );
        return $years;
    }

    public function initializeViewVariables($query, $view, $year) {
    	$rows = array();
        $branches = array('' => 'Alle');
		$consultants = array('' => 'Alle');
        foreach ($query->execute() as $customer) {
        	$rows[] = new CustomerSurveyYear($customer, $year);
        	if ($customer->getBranch() !== NULL) {
        		$branches[$customer->getBranch()->getName()] = $customer->getBranch()->getName();
        	}
        	if ($customer->getConsultant() !== NULL) {
        		$consultants[$customer->getConsultant()->getUsername()] = $customer->getConsultant()->__toString();
        	}
        }
        $view->assign('rows', $rows);
        $view->assign('branches', $branches);
        $view->assign('consultants', $consultants);
    }

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function happinessAction() {
		$query = $this->customerRepository->createQuery(FALSE);

		$threshold = new \DateTime('-30d');
		$query->matching($query->logicalAnd(
			$query->equals('consultant', $this->securityContext->getParty()),
			$query->logicalOr(
				$query->greaterThan('termination', $threshold),
				$query->equals('termination', NULL)
			)
		));

		$this->view->assign('customers', $query->execute());

		foreach ($query->execute() as $customer) {
			// var_dump($customer->__toString());
		}

		$this->view->assign('week', intval(date('W')));
	}

	/**
	 * Index action
	 *
	 * @param array $ratings
	 * @return void
	 */
	public function saveHappinessAction($ratings = array()) {
		if (empty($ratings)) {
			$this->redirect('happiness');
		}
		foreach ($ratings as $identifier => $data) {
			$customer = $this->customerRepository->findByIdentifier($identifier);
			if ($customer->getRatingForThisWeek() !== NULL) {
				$rating = $customer->getRatingForThisWeek();
			} else {
				$rating = new \Famelo\ADU\Domain\Model\Rating();
			}
			if (!isset($data['value'])) {
				continue;
			}
			$rating->setLevel($data['value']);
			if (isset($data['comment'])) {
				$rating->setComment($data['comment']);
			}
			if (isset($data['action'])) {
				$rating->setAction($data['action']);
			}
			if (isset($data['date'])) {
				try {
					$rating->setDate(new \DateTime($data['date']));
				} catch (\Exception $e) {

				}
			}
			$rating->setCustomer($customer);
			if ($this->persistenceManager->isNewObject($rating) === FALSE) {
				$this->persistenceManager->update($rating);
			} else {
				$this->persistenceManager->add($rating);
			}

			$customer->setSelfEvaluationResult($data['value']);
			$customer->calculateSelfEvaluationResult();
			$this->persistenceManager->update($customer);
		}
	}

	/**
	 * @return void
	 */
	public function ratingsAction() {
	}

	/**
	 * New action
	 *
	 * @return void
	 */
	public function newAction() {
		$survey = new \Famelo\ADU\Domain\Model\Survey();
		$branch = $this->securityContext->getParty()->getBranch();
		if (!$branch instanceof \Famelo\ADU\Domain\Model\Branch) {
			$branch = $this->branchRepository->findOneByName('ADU');
		}
		foreach ($branch->getMatchingQuestions() as $question) {
			$answer = new \Famelo\ADU\Domain\Model\Answer();
			$answer->setQuestion($question);
			$survey->addAnswer($answer);
		}
		$this->view->assign('survey', $survey);
	}

	/**
	 * create action
	 *
	 * @return void
	 */
	public function createAction() {
		$surveys = $this->request->getInternalArgument('__objects');
		foreach ($surveys as $survey) {
			$this->persistenceManager->add($survey);
		}

		if (in_array($survey->getResultColor(), array('orange', 'red'))) {
			$mail = new \Famelo\Messaging\Message();
			$mail
				->setFrom(array('no-reply@adu-kundenzufriedenheit.de' => 'ADU Kundenzufriedenheit'))
				->setSubject('Der Kunde ' . $survey->getCustomer()->getName() . ' ist laut einer Befragung unzufrieden')
				->setMessage('Famelo.ADU:NewProblematicSurvey')
				->assign('consultant', $this->securityContext->getParty())
				->assign('survey', $survey);


			if (is_array($this->settings['MailRecipients']['NewProblematicSurvey'])) {
				foreach ($this->settings['MailRecipients']['NewProblematicSurvey'] as $recipient) {
					$mail->setTo(array($recipient));
					$mail->send();
				}
			}
		}

		if ($survey->getMoreSecurity() || $survey->getMoreService()) {
			$mail = new \Famelo\Messaging\Message();
			$mail
				->setFrom(array('no-reply@adu-kundenzufriedenheit.de' => 'ADU Kundenzufriedenheit'))
				->setSubject('Anfrage nach mehr Informationen')
				->setMessage('Famelo.ADU:MoreInformation')
				->assign('survey', $survey);

			$mail->setTo(array('m.keller@adu-urban.de'));
			$mail->send();
		}

		$mail = new \Famelo\Messaging\Message();
		$mail
			->setFrom(array('no-reply@adu-kundenzufriedenheit.de' => 'ADU Kundenzufriedenheit'))
			->setSubject('Vielen Dank für Ihre Bewertung')
			->setMessage('Famelo.ADU:NotifyCustomerAboutSurvey')
			->assign('survey', $survey);

		if ($survey->getContact() instanceof \Famelo\ADU\Domain\Model\Contact) {
			$mail->setTo(array($survey->getContact()->getEmail()));
			$mail->send();
		}

		$this->persistenceManager->persistAll();
	}

	/**
	 * @param \Famelo\ADU\Domain\Model\Survey $survey
	 * @return void
	 */
	public function completeAction($survey) {}
}

?>
