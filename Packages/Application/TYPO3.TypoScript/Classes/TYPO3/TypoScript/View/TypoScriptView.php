<?php
namespace TYPO3\TypoScript\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TypoScript".            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * View for using TypoScript for standard MVC controllers.
 *
 * Loads all TypoScript files from the current package Resources/Private/TypoScript
 * folder (recursively); and then checks whether a TypoScript object for current
 * controller and action can be found.
 *
 * If the controller class name is Foo\Bar\Baz\Controller\BlahController and the action is "index",
 * it checks for the TypoScript path Foo.Bar.Baz.BlahController.index.
 * If this path is found, then it is used for rendering. Otherwise, the $fallbackView
 * is used.
 */
class TypoScriptView extends \TYPO3\Flow\Mvc\View\AbstractView {
	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'typoScriptPathPatterns' => array(array('resource://@package/Private/TypoScripts/'), 'TypoScript files will be recursively loaded from this paths.', 'array'),
		'typoScriptPath' => array(NULL, 'The TypoScript path which should be rendered; derived from the controller and action names or set by the user.', 'string'),
		'packageKey' => array(NULL, 'The package key where the TypoScript should be loaded from. If not given, is automatically derived from the current request.', 'string')
	);

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TypoScript\Core\Parser
	 */
	protected $typoScriptParser;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\View\ViewInterface
	 */
	protected $fallbackView;

	/**
	 * TypoScript files will be recursively loaded from this paths
	 *
	 * @var array<typoScripPaths>
	 */
	protected $typoScriptPathPatterns = array(
		'resource://@package/Private/TypoScripts/'
	);

	/**
	 * The parsed TypoScript array in its internal representation
	 *
	 * @var array
	 */
	protected $parsedTypoScript;

	/**
	 * The TypoScript path which should be rendered; derived from the controller
	 * and action names or set by the user.
	 *
	 * @var string
	 */
	protected $typoScriptPath = NULL;

	/**
	 * The package key where the TypoScript should be loaded from. If not given,
	 * is automatically derived from the current request.
	 *
	 * @var string
	 */
	protected $packageKey = NULL;

	/**
	 * if FALSE, the fallback view will never be used.
	 *
	 * @var boolean
	 */
	protected $fallbackViewEnabled = TRUE;

	/**
	 * The TypoScript Runtime
	 *
	 * @var \TYPO3\TypoScript\Core\Runtime
	 */
	protected $typoScriptRuntime = NULL;

	/**
	 * Sets the TypoScript path to be rendered to an explicit value;
	 * to be used mostly inside tests.
	 *
	 * @param string $typoScriptPath
	 * @return void
	 */
	public function setTypoScriptPath($typoScriptPath) {
		$this->typoScriptPath = $typoScriptPath;
	}

	/**
	 * Returns the current typoScripPath to be rendered
	 *
	 * @return string typoScriptPath
	 */
	public function getTypoScriptPath() {
		return $this->typoScriptPath;
	}

	/**
	 * Sets the path patterns to load TypoScripts from
	 *
	 * @param array $typoScriptPathPatterns
	 * @return void
	 */
	public function setTypoScriptPathPatterns($typoScriptPathPatterns) {
		$this->typoScriptPathPatterns = $typoScriptPathPatterns;
	}

	/**
	 * The package key where the TypoScript should be loaded from. If not given,
	 * is automatically derived from the current request.
	 *
	 * @param string $packageKey
	 * @return void
	 */
	public function setPackageKey($packageKey) {
		$this->packageKey = $packageKey;
	}

	/**
	 * Returns the initialized TypoScript Runtime
	 *
	 * @return \TYPO3\TypoScript\Core\Runtime
	 */
	public function getTypoScriptRuntime() {
		$this->initializeTypoScriptRuntime();
		return $this->typoScriptRuntime;
	}

	/**
	 * Disable the use of the Fallback View
	 *
	 * @return void
	 */
	public function disableFallbackView() {
		$this->fallbackViewEnabled = FALSE;
	}

	/**
	 * Re-Enable the use of the Fallback View. By default, it is enabled,
	 * so calling this method only makes sense if disableFallbackView() has
	 * been called before.
	 *
	 * @return void
	 */
	public function enableFallbackView() {
		$this->fallbackViewEnabled = TRUE;
	}

	/**
	 * Render the view
	 *
	 * @return string The rendered view
	 * @api
	 */
	public function render() {
		$this->initializeTypoScriptRuntime();
		if ($this->typoScriptRuntime->canRender($this->typoScriptPath) || $this->fallbackViewEnabled === FALSE) {
			return $this->renderTypoScript();
		} else {
			return $this->renderFallbackView();
		}
	}

	/**
	 * Load the TypoScript Files form the defined
	 * paths and construct a Runtime from the
	 * parsed results
	 *
	 * @return void
	 */
	public function initializeTypoScriptRuntime() {
		if ($this->typoScriptRuntime === NULL) {
			$this->loadTypoScript();

			$this->initializeTypoScriptPathForCurrentRequest();

			$this->typoScriptRuntime = new \TYPO3\TypoScript\Core\Runtime($this->parsedTypoScript, $this->controllerContext);
		}
	}

	/**
	 * Load TypoScript from the directories specified by $this->typoScriptPathPatterns
	 *
	 * @return void
	 */
	protected function loadTypoScript() {
		$mergedTypoScriptCode = '';
		ksort($this->typoScriptPathPatterns);
		foreach ($this->typoScriptPathPatterns as $typoScriptPathPattern) {
			$typoScriptPathPattern = str_replace('@package', $this->getPackageKey(), $typoScriptPathPattern);
			$filePaths = \TYPO3\Flow\Utility\Files::readDirectoryRecursively($typoScriptPathPattern, '.ts2');
			sort($filePaths);
			foreach ($filePaths as $filePath) {
				$mergedTypoScriptCode .= PHP_EOL . file_get_contents($filePath) . PHP_EOL;
			}
		}
		$this->parsedTypoScript = $this->typoScriptParser->parse($mergedTypoScriptCode);
	}

	/**
	 * Get the package key to load the TypoScript from. If set, $this->packageKey is used.
	 * Otherwise, the current request is taken and the controller package key is extracted
	 * from there.
	 *
	 * @return string the package key to load TypoScript from
	 */
	protected function getPackageKey() {
		if ($this->packageKey !== NULL) {
			return $this->packageKey;
		} else {
			return $this->controllerContext->getRequest()->getControllerPackageKey();
		}
	}

	/**
	 * Initialize $this->typoScriptPath depending on the current controller and action
	 *
	 * @return void
	 */
	protected function initializeTypoScriptPathForCurrentRequest() {
		if ($this->typoScriptPath === NULL) {
			$request = $this->controllerContext->getRequest();
			$typoScriptPathForCurrentRequest = $request->getControllerObjectName();
			$typoScriptPathForCurrentRequest = str_replace('\\Controller\\', '\\', $typoScriptPathForCurrentRequest);
			$typoScriptPathForCurrentRequest = str_replace('\\', '/', $typoScriptPathForCurrentRequest);
			$typoScriptPathForCurrentRequest = trim($typoScriptPathForCurrentRequest, '/');
			$typoScriptPathForCurrentRequest .= '/' . $request->getControllerActionName();

			$this->typoScriptPath = $typoScriptPathForCurrentRequest;
		}
	}

	/**
	 * Determine whether we are able to find TypoScript at the requested position
	 *
	 * @return boolean TRUE if TypoScript exists at $this->typoScriptPath; FALSE otherwise
	 */
	protected function isTypoScriptFoundForCurrentRequest() {
		return (\TYPO3\Flow\Utility\Arrays::getValueByPath($this->parsedTypoScript, str_replace('/', '.', $this->typoScriptPath)) !== NULL);
	}

	/**
	 * Render the given TypoScript and return the rendered page
	 *
	 * @return string
	 */
	protected function renderTypoScript() {
		$this->typoScriptRuntime->pushContextArray($this->variables);
		$output = $this->typoScriptRuntime->render($this->typoScriptPath);
		$this->typoScriptRuntime->popContext();
		return $output;
	}

	/**
	 * Initialize and render the fallback view
	 *
	 * @return string
	 */
	public function renderFallbackView() {
		$this->fallbackView->setControllerContext($this->controllerContext);
		$this->fallbackView->assignMultiple($this->variables);
		return $this->fallbackView->render();
	}
}
?>