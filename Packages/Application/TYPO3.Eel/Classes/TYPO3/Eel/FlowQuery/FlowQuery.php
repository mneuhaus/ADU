<?php
namespace TYPO3\Eel\FlowQuery;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Eel".             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * FlowQuery is jQuery for PHP, a selector and traversal engine for object sets.
 *
 * It is specifically implemented for being used inside Eel, the Embedded Expression
 * Language for Flow.
 *
 * Essentially, a FlowQuery object is a container for an *ordered set* of objects
 * of a certain type. On this container, *operations* can be applied (like
 * filter(), children(), ...).
 *
 * All of these operations work on a *set*, that is, an operation usually expands
 * or shrinks the set of objects.
 *
 * An operation normally returns a new FlowQuery instance with the operation applied,
 * but there are also some operations like is(...) or count(), which return simple
 * types like boolean or numbers. We call these operations *final operations*.
 *
 * Internal Workings
 * =================
 *
 * To allow for optimization, calling operations are not immediately executed.
 * Instead, they are appended to a *list of operations*. Only if one tries to
 * iterate over the FlowQuery object or calls a final operation, the operations
 * get executed and the result is computed.
 *
 * Implementation of Operations
 * ----------------------------
 *
 * Operations are implemented by implementing the {@link OperationInterface} or,
 * more commonly, subclassing the {@link Operations/AbstractOperation}.
 *
 * An operation must be *equivalence preserving*, that is, the following equation
 * must always hold:
 *
 * applyAllOperations(context) = applyRemainingOperations(applyOperation(context))
 *
 * While an operation is running, it can *add new operations* to the front of the
 * operation queue (with {@link pushOperation()}), so for example count($filter)
 * can first apply filter($filter), followed by count(). However, when doing this,
 * great care must be applied by the operation developer in order to still have
 * finite runs, and to make sure the operation is *equivalence preserving*.
 *
 * Furthermore, an operation can *pop* its following operations from the operation
 * stack, and *peek* what the next operation is. It is up to the operation developer
 * to ensure equivalence preservation.
 *
 * An operation may *never* invoke __call() on the FlowQuery object it receives;
 * as this might lead to an undefined state; i.e. you are not allowed to do:
 * $flowQuery->someOtherOperation() *inside* an operation.
 *
 * Final Operations
 * ----------------
 *
 * If an operation is final, it should return the resulting value directly.
 */
class FlowQuery implements \IteratorAggregate, \Countable {

	/**
	 * the objects this FlowQuery object wraps
	 *
	 * @var array|Traversable
	 */
	protected $context;

	/**
	 * Ordered list of operations, each operation is internally
	 * represented as array('name' => '...', 'arguments' => array(...))
	 * whereas the name is a string like 'children' and the arguments
	 * are a numerically-indexed array
	 *
	 * @var array
	 */
	protected $operations = array();

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Eel\FlowQuery\OperationResolver
	 */
	protected $operationResolver;

	/**
	 * Construct a new FlowQuery object from $context and $operations.
	 *
	 * Only the $context parameter belongs to the public API!
	 *
	 * @param array $context
	 * @param array $operations
	 * @api
	 */
	public function __construct(array $context, array $operations = array()) {
		$this->context = $context;
		$this->operations = $operations;
	}

	/**
	 * Setter for setting the operation resolver from the outside, only needed
	 * to successfully run unit tests (hacky!)
	 *
	 * @param OperationResolver $operationResolver
	 */
	public function setOperationResolver(OperationResolver $operationResolver) {
		$this->operationResolver = $operationResolver;
	}

	/**
	 * Add a new operation to the operation list and return the new FlowQuery
	 * object. If the operation is final, we directly compute the result and
	 * return the value.
	 *
	 * @param string $operationName
	 * @param array $arguments
	 * @return \TYPO3\Eel\FlowQuery\FlowQuery
	 */
	public function __call($operationName, array $arguments) {
		$updatedOperations = $this->operations;
		$updatedOperations[] = array(
			'name' => $operationName,
			'arguments' => $arguments
		);

		if ($this->operationResolver->isFinalOperation($operationName)) {
			$operationsBackup = $this->operations;
			$contextBackup = $this->context;

			$this->operations = $updatedOperations;
			$operationResult = $this->evaluateOperations();
			$this->operations = $operationsBackup;
			$this->context = $contextBackup;

			return $operationResult;
		} else {
			// non-final operation
			$flowQuery = new FlowQuery($this->context, $updatedOperations);
			$flowQuery->setOperationResolver($this->operationResolver); // Only needed for unit tests; hacky!
			return $flowQuery;
		}
	}

	/**
	 * Implementation of the countable() interface, which is mapped to the "count" operation.
	 *
	 * @return integer
	 */
	public function count() {
		return $this->__call('count', array());
	}

	/**
	 * Called when iterating over this FlowQuery object, triggers evaluation.
	 *
	 * Should NEVER be called inside an operation!
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator() {
		if (count($this->operations) > 0) {
			$this->evaluateOperations();
		}
		return new \ArrayIterator($this->context);
	}

	/**
	 * Evaluate operations
	 *
	 * @return mixed the last operation result if the operation is a final operation, NULL otherwise
	 */
	protected function evaluateOperations() {
		while ($op = array_shift($this->operations)) {
			$operation = $this->operationResolver->resolveOperation($op['name'], $this->context);
			$lastOperationResult = $operation->evaluate($this, $op['arguments']);
		}
		return $lastOperationResult;
	}

	/**
	 * Pop the topmost operation from the stack and return it; i.e. the
	 * operation which should be executed next. The returned array has
	 * the form:
	 * array('name' => '...', 'arguments' => array(...))
	 *
	 * Should only be called inside an operation.
	 *
	 * @return array
	 */
	public function popOperation() {
		return array_shift($this->operations);
	}

	/**
	 * Push a new operation onto the operations stack.
	 *
	 * The last-pushed operation is executed FIRST! (LIFO)
	 *
	 * Should only be called inside an operation.
	 *
	 * @param string $operationName
	 * @param string $arguments
	 */
	public function pushOperation($operationName, array $arguments) {
		array_unshift($this->operations, array(
			'name' => $operationName,
			'arguments' => $arguments
		));
	}

	/**
	 * Peek onto the next operation name, if any, or NULL otherwise.
	 *
	 * Should only be called inside an operation.
	 *
	 * @return string the next operation name or NULL if no next operation found.
	 */
	public function peekOperationName() {
		if (isset($this->operations[0])) {
			return $this->operations[0]['name'];
		} else {
			return NULL;
		}
	}

	/**
	 * Get the current context.
	 *
	 * Should only be called inside an operation.
	 *
	 * @return array|Traversable
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * Set the updated context with the operation result applied.
	 *
	 * Should only be called inside an operation.
	 *
	 * @param $context array|Traversable
	 */
	public function setContext($context) {
		$this->context = $context;
	}
}
?>