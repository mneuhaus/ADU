<?php
namespace Famelo\ADU\Controller;

/*                                                                        *
 * This script belongs to the FLOW3 package "Famelo.ADU".                 *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Famelo.ADU package 
 *
 * @Flow\Scope("singleton")
 */
class LoginController extends \TYPO3\Flow\Mvc\Controller\ActionController {
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
	 *
	 *
	 * @return string
	 */
	public function indexAction() {
	}

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 *
	 * On successful authentication redirects to the list of posts, otherwise returns
	 * to the login screen.
	 *
	 * @return void
	 * @throws \TYPO3\Flow\Security\Exception\AuthenticationRequiredException
	 */
	public function authenticateAction() {
		try {
			$this->authenticationManager->authenticate();
			$this->redirect('index', 'Standard');
		} catch (\TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception) {
			$this->addFlashMessage('Wrong username or password.');
			throw $exception;
		}
	}

	/**
	 *
	 * @return void
	 */
	public function logoutAction() {
		$this->authenticationManager->logout();
		$this->addFlashMessage('Successfully logged out.');
		$this->redirect('index');
	}

	/**
	 *
	 * @return void
	 */
	public function changePasswordAction() {}

	/**
	 *
	 * @return void
	 */
	public function savePasswordAction() {
		$objects = $this->request->getInternalArgument('__objects');
		foreach ($objects as $object) {
			$this->persistenceManager->update($object);
		}
		$this->persistenceManager->persistAll();
	}
}

?>