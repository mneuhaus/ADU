<?php
namespace Famelo\ADU\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * adu command controller for the Famelo.ADU package
 *
 * @Flow\Scope("singleton")
 */
class AduCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 * @Flow\Inject
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @Flow\Inject
	 * @var \Famelo\ADU\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @Flow\Inject
	 * @var \Famelo\ADU\Domain\Repository\CustomerRepository
	 */
	protected $customerRepository;

	/**
	 * @Flow\Inject
	 * @var \Famelo\ADU\Domain\Repository\BranchRepository
	 */
	protected $branchRepository;

	/**
	 * @Flow\Inject
	 * @var \Famelo\ADU\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Create a new User for the ADU Package
	 *
	 * The comment of this command method is also used for TYPO3 Flow's help screens. The first line should give a very short
	 * summary about what the command does. Then, after an empty line, you should explain in more detail what the command
	 * does. You might also give some usage example.
	 *
	 * It is important to document the parameters with param tags, because that information will also appear in the help
	 * screen.
	 *
	 * @param string $username
	 * @param string $password
	 * @return void
	 */
	public function createUserCommand($username, $password) {
		$roles = array('Administrator');
		$authenticationProviderName = 'ADUProvider';

		$account = $this->accountFactory->createAccountWithPassword($username, $password, $roles, $authenticationProviderName);
		$this->accountRepository->add($account);
	}

	/**
	 * Create a new User for the ADU Package
	 *
	 * Fake some Data :)
	 *
	 * @return void
	 */
	public function fakeDataCommand() {
		$branches = array(
			'Rhein/Ruhr',
			'Hannover',
			'OWL/Hessen'
		);
		$faker = \Faker\Factory::create('de_DE');

		foreach ($branches as $branchName) {
			$branch = new \Famelo\ADU\Domain\Model\Branch();
			$branch->setName($branchName);
			$this->persistenceManager->add($branch);

			for ($i = 0; $i < 25; $i++) {
				$customer = new \Famelo\ADU\Domain\Model\Customer();
				$customer->setBranch($branch);
				$customer->setName($faker->company);

				$contact = new \Famelo\ADU\Domain\Model\Contact();
				$contact->setFirstname($faker->firstName);
				$contact->setLastname($faker->lastName);
				$contact->setPhone($faker->phoneNumber);
				$contact->setEmail($faker->email);
				$customer->setContact($contact);
				$this->persistenceManager->add($contact);

				$contact = new \Famelo\ADU\Domain\Model\Contact();
				$contact->setFirstname($faker->firstName);
				$contact->setLastname($faker->lastName);
				$contact->setPhone($faker->phoneNumber);
				$contact->setEmail($faker->email);
				$customer->setAlternativeContact($contact);
				$this->persistenceManager->add($contact);

				$this->persistenceManager->add($customer);
			}
		}
	}

	/**
	 * Update Data
	 *
	 * @return void
	 */
	public function migrateDataCommand() {
		foreach ($this->customerRepository->findAll() as $customer) {
			if ($customer->getCreated() === NULL) {
				$customer->setCreated(new \DateTime());
				$this->persistenceManager->update($customer);
			}
		}
	}

	/**
	 * Update Data
	 *
	 * @return void
	 */
	public function statsCommand() {
		foreach ($this->customerRepository->findAll() as $customer) {
			// $customer->setSatisfaction(intval($customer->getRatingSum()));
			$customer->calculateSelfEvaluationResult();
			$this->persistenceManager->update($customer);
		}
	}

	/**
	 * Import Fixtures
	 *
	 * @param bool $startFresh
	 * @return void
	 */
	public function importFixturesCommand($startFresh = FALSE) {
		//$this->removeAllEntities('\Famelo\ADU\Domain\Model\Rating');
		//$this->removeAllEntities('\Famelo\ADU\Domain\Model\Answer');
		//$this->removeAllEntities('\TYPO3\Flow\Security\Account');
		//$this->removeAllEntities('\Famelo\ADU\Domain\Model\User');
		//$this->removeAllEntities('\Famelo\ADU\Domain\Model\Survey');
		//$this->removeAllEntities('\Famelo\ADU\Domain\Model\Customer');
		//$this->removeAllEntities('\Famelo\ADU\Domain\Model\Contact');

		$customers = $this->getFixture('Kunden');

		$users = $this->getFixture('Mitarbeiter');
		$this->outputLine('Importing Users', array(), 'green');
		$references = array();
		foreach ($users as $userData) {
			$names = explode(',', $userData['Name']);
			$firstName = trim($names[1]);
			$lastName = trim($names[0]);

			$roles = array($userData['Abteilung/Funktion']);
			$authenticationProviderName = 'ADUProvider';
			$password = $this->generatePassword();
			$username = strtolower(substr($firstName, 0, 1) . $lastName);
			$account = $this->accountFactory->createAccountWithPassword($username, $password, $roles, $authenticationProviderName);
			$account->setRoles($roles);
			$this->outputLine('Created User: ' . $username . ' [' . $password . ']', array(), 'green');

			$user = new \Famelo\ADU\Domain\Model\User();
			$user->setOrigin('import');
			$user->setAccounts(array($account));
			$account->setParty($user);
			$user->setEmail($userData['E-Mail']);
			$user->setName(new \TYPO3\Party\Domain\Model\PersonName('', $firstName, '', $lastName));
			if (!empty($userData['Bereich'])) {
				$user->setBranch($this->branchRepository->getOrCreate(trim($userData['Bereich'])));
			}
			$this->persistenceManager->add($user);
			$references[$lastName] = $user;

			$mail = new \Famelo\Messaging\Message();
			$mail
				->setFrom(array('mneuhaus@famelo.com' => 'ADU'))
				->setSubject('Zugangsdaten zum ADU Bewertungsportal')
				->setMessage('Famelo.ADU:UserCreated')
				->assign('user', $user)
				->assign('password', $password)
				->assign('greeting', $userData['Anrede']);

			$mail->setTo(array('mneuhaus@famelo.com'));
			$mail->send();

			$mail->setTo(array('jkunter@famelo.com'));
			$mail->send();

			$mail->setTo(array('b.janz@adu-urban.de'));
			$mail->send();

			$mail->setTo(array('patrick.bremehr@neuland-medien.de'));
			$mail->send();
		}

		$this->outputLine('Importing Customers', array(), 'green');
		foreach ($customers as $customerData) {
			if (!isset($references[$customerData['Kundenbetreuer']])) {
				$this->outputLine('Missing User: ' . $customerData['Kundenbetreuer'], array(), 'yellow');
				continue;
			}
			$user = $references[$customerData['Kundenbetreuer']];

			$customer = new \Famelo\ADU\Domain\Model\Customer();
			$customer->setName($customerData['Name']);
			$customer->setObject($customerData['Objekt']);
			$customer->setCategory($this->categoryRepository->getOrCreate(trim($customerData['Kategorie'])));
			$customer->setConsultant($user);
			$customer->setBranch($user->getBranch());
			$created = new \DateTime('01.12.2012');
			$customer->setCreated($created);
			$this->persistenceManager->add($customer);
		}
	}

	/**
	 * Import Fixtures
	 *
	 * @param string $fixture
	 * @return void
	 */
	public function updateFromFixturesCommand($fixture) {
		$customers = array();
		foreach ($this->customerRepository->findAll() as $customer) {
			$customers[$customer->getName() . '|' . $customer->getObject()] = $customer;
			$customers[$customer->getName()] = $customer;
		}
		ksort($customers);
		$fixtures = $this->getFixture($fixture);
		foreach ($fixtures as $fixture) {
			$key = $fixture['Knd Name 1'] . '|' . $fixture['Objekt'];
			unset($customer);

			if (isset($customers[$key])) {
				$this->outputLine('Object found: ' . $key, array(), 'green');
				$customer = $customers[$key];
			} elseif (isset($customers[$fixture['Knd Name 1']])) {
				$this->outputLine('Object found: ' . $fixture['Knd Name 1'], array(), 'green');
				$customer = $customers[$fixture['Knd Name 1']];
			}

			if (isset($customer)) {
				$cycles = array(
					'Monatlich' => 30,
					'Quartalsweise' => 90,
					'Halbjährlich' => 180,
					'jährlich' => 365
				);
				if (isset($cycles[$fixture['Rythmen']])) {
					$customer->setCycle($cycles[$fixture['Rythmen']]);
				}

				$contact = $customer->getContact();
				if (!$contact instanceof \Famelo\ADU\Domain\Model\Contact) {
					$contact = new \Famelo\ADU\Domain\Model\Contact();
					$customer->setContact($contact);
				}
				$contact->setFirstname($fixture['Vorname']);
				$contact->setLastname($fixture['Nachname']);
				$contact->setEmail($fixture['E-Mail']);
				$contact->setPhone($fixture['Telefonnummer']);

				$contact = $customer->getAlternativeContact();
				if (!$contact instanceof \Famelo\ADU\Domain\Model\Contact) {
					$contact = new \Famelo\ADU\Domain\Model\Contact();
					$customer->setAlternativeContact($contact);
				}
				$contact->setFirstname($fixture['Vorname2']);
				$contact->setLastname($fixture['Nachname2']);
				$contact->setEmail($fixture['E-Mail2']);
				$contact->setPhone($fixture['Telefonnummer2']);

				$this->persistenceManager->update($customer);
			} else {
				$this->outputLine('Object not found: ' . $key, array(), 'yellow');
			}
		}
		return;
		foreach ($customers as $customerData) {
			if (!isset($references[$customerData['Kundenbetreuer']])) {
				$this->outputLine('Missing User: ' . $customerData['Kundenbetreuer'], array(), 'yellow');
				continue;
			}
			$user = $references[$customerData['Kundenbetreuer']];

			$customer = new \Famelo\ADU\Domain\Model\Customer();
			$customer->setName($customerData['Name']);
			$customer->setObject($customerData['Objekt']);
			$customer->setCategory($this->categoryRepository->getOrCreate(trim($customerData['Kategorie'])));
			$customer->setConsultant($user);
			$customer->setBranch($user->getBranch());
			$created = new \DateTime('01.12.2012');
			$customer->setCreated($created);
			$this->persistenceManager->add($customer);
		}
	}

	public function removeAllEntities($entity) {
		$this->outputLine('Removing all entities of type: ' . $entity, array(), 'red');
		$query = $this->persistenceManager->createQueryForType($entity);
		$objects = $query->execute();
		foreach ($objects as $object) {
			if (method_exists($object, 'getOrigin') && $object->getOrigin() !== 'import') {
				continue;
			}
			$this->persistenceManager->remove($object);
		}
		$this->persistenceManager->persistAll();
	}

	/**
	 * Outputs specified text to the console window and appends a line break
	 *
	 * @param string $text Text to output
	 * @param array $arguments Optional arguments to use for sprintf
	 * @return void
	 * @see output()
	 * @see outputLines()
	 */
	protected function outputLine($text = '', array $arguments = array(), $color = NULL) {
		if ($color !== NULL) {
			$colors = array(
				'green' => '0;32',
				'red' => '0;31',
				'yellow' => '0;33'
			);
			$text = sprintf("\033[%sm%s\033[0m", $colors[$color], $text);
		}
		echo $text . chr(10);
	}

	public function getFixture($name) {
		$fixturesPath = FLOW_PATH_ROOT . '/Data/Fixtures/';

		$handle = fopen($fixturesPath . $name . '.csv', 'r');

		$fixtures = array();
		while (($row = fgetcsv($handle, 1000, ';')) !== FALSE) {
			if (isset($headers)) {
				$fixture = array();
				foreach ($row as $key => $value) {
					$fixture[$headers[$key]] = $value;
				}
				$fixtures[] = $fixture;
			} else {
				$headers = $row;
			}
		}

		fclose($handle);

		return $fixtures;
	}

	public function generatePassword() {
		$pw = '';
		$c = 'bcdfghjklmnprstvwz';
		$v = 'aeiou';
		$a = $c . $v;

		for ($i = 0; $i < 2; $i++) {
			$pw.= $c[rand(0, strlen($c)-1)];
			$pw.= $v[rand(0, strlen($v)-1)];
			$pw.= $a[rand(0, strlen($a)-1)];
		}
		$pw.= rand(10, 99);

		return $pw;
	}
}

?>