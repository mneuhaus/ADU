<?php

require_once dirname(__FILE__) . '/../../../Packages/Application/Famelo.Poltergeist/Resources/Private/FlowContext.php';

/**
 * Features context.
 */
class FeatureContext extends FlowContext {
	/**
     * @Given /^I log in as "([^"]*)" with the password "([^"]*)"$/
     */
    public function iAmLoggedInAsWithThePassword($username, $password) {
    	$this->iAmOnHomepage();
    	$this->fillField('__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][username]', $username);
    	$this->fillField('__authentication[TYPO3][Flow][Security][Authentication][Token][UsernamePassword][password]', $password);
    	$this->pressButton('Login');
    }
}
