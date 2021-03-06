<?php
namespace Famelo\Navigation\Aop;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * In Flow the join point object contains context information when a point cut
 * matches and the registered advices are executed.
 *
 * @api
 */
class VirtualJoinPoint implements \TYPO3\Flow\Aop\JoinPointInterface {

	/**
	 * Class name of the target class this join point refers to
	 * @var string
	 */
	protected $className;

	/**
	 * Method name of the target method which is about to or has been invoked
	 * @var string
	 */
	protected $methodName;


	/**
	 * Returns the reference to the proxy class instance
	 *
	 * @return \TYPO3\Flow\Object\Proxy\ProxyInterface
	 */
	public function getProxy() {

	}

	/**
	 * Returns the class name of the target class this join point refers to
	 *
	 * @return string The class name
	 */
	public function getClassName() {
		return $this->className;
	}

	public function setClassName($className) {
		$this->className = $className;
	}

	/**
	 * Returns the method name of the method this join point refers to
	 *
	 * @return string The method name
	 */
	public function getMethodName() {
		return $this->methodName;
	}

	public function setMethodName($methodName) {
		$this->methodName = $methodName;
	}

	/**
	 * Returns an array of arguments which have been passed to the target method
	 *
	 * @return array Array of arguments
	 */
	public function getMethodArguments() {

	}

	/**
	 * Returns the value of the specified method argument
	 *
	 * @param  string $argumentName Name of the argument
	 * @return mixed Value of the argument
	 */
	public function getMethodArgument($argumentName) {

	}

	/**
	 * Returns TRUE if the argument with the specified name exists in the
	 * method call this joinpoint refers to.
	 *
	 * @param string $argumentName Name of the argument to check
	 * @return boolean TRUE if the argument exists
	 */
	public function isMethodArgument($argumentName) {

	}

	/**
	 * Sets the value of the specified method argument
	 *
	 * @param string $argumentName Name of the argument
	 * @param mixed $argumentValue Value of the argument
	 * @return void
	 */
	public function setMethodArgument($argumentName, $argumentValue) {

	}

	/**
	 * Returns the advice chain related to this join point
	 *
	 * @return \TYPO3\Flow\Aop\Advice\AdviceChain The advice chain
	 */
	public function getAdviceChain() {

	}

	/**
	 * If an exception was thrown by the target method
	 * Only makes sense for After Throwing advices.
	 *
	 * @return boolean
	 */
	public function hasException() {

	}

	/**
	 * Returns the exception which has been thrown in the target method.
	 * If no exception has been thrown, NULL is returned.
	 * Only makes sense for After Throwing advices.
	 *
	 * @return \Exception The exception thrown or NULL
	 */
	public function getException() {

	}

	/**
	 * Returns the result of the method invocation. The result is only
	 * available for AfterReturning advices.
	 *
	 * @return mixed Result of the method invocation
	 */
	public function getResult() {

	}
}

?>