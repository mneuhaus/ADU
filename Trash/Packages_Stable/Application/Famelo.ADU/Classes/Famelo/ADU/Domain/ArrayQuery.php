<?php
namespace Famelo\ADU\Domain;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Expose".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A Query wrapper for Arrays
 */
class ArrayQuery extends \TYPO3\Flow\Persistence\Doctrine\Query {
	/**
	 * @var array
	 */
	protected $array;

	public function setArray($array) {
		$this->array = $array;
	}

	/**
	 * Gets the results of this query as array.
	 * Really executes the query on the database.
	 * This should only ever be executed from the QueryResult class.
	 *
	 * @return array result set
	 */
	public function getResult() {
		return array_slice($this->array, $this->getOffset(), $this->getLimit());
	}

	public function count() {
		return count($this->array);
	}
}

?>