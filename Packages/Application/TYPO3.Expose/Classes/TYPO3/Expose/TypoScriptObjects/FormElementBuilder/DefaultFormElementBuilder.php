<?php
namespace TYPO3\Expose\TypoScriptObjects\FormElementBuilder;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Expose".               *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Render a Form section using the Form framework
 */
class DefaultFormElementBuilder extends \TYPO3\TypoScript\TypoScriptObjects\AbstractTypoScriptObject {

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var string
	 */
	protected $identifier;

	/**
	 * @var \TYPO3\Form\Core\Model\AbstractSection
	 */
	protected $parentFormElement;

	/**
	 * @var string
	 */
	protected $formFieldType;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var array
	 */
	protected $propertyAnnotations;

	/**
	 * @var string
	 */
	protected $propertyName;

	/**
	 * @var string
	 */
	protected $className;

	/**
	 * @var string
	 */
	protected $propertyType;

	/**
	 * @var object
	 */
	protected $formBuilder;

	/**
	 * @var object
	 */
	protected $propertyValue;

	/**
	 * @var array
	 */
	protected $propertySchema;

	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}

	/**
	 * @param mixed $parentFormElement
	 * @return void
	 */
	public function setParentFormElement($parentFormElement) {
		$this->parentFormElement = $parentFormElement;
	}

	/**
	 * @param string $formFieldType
	 * @return void
	 */
	public function setFormFieldType($formFieldType) {
		$this->formFieldType = $formFieldType;
	}

	/**
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * @param array $propertyAnnotations
	 * @return void
	 */
	public function setPropertyAnnotations(array $propertyAnnotations) {
		$this->propertyAnnotations = $propertyAnnotations;
	}

	/**
	 * @param string $propertyName
	 * @return void
	 */
	public function setPropertyName($propertyName) {
		$this->propertyName = $propertyName;
	}

	/**
	 * @param string $className
	 * @return void
	 */
	public function setClassName($className) {
		$this->className = $className;
	}

	/**
	 * @param string $propertyType
	 * @return void
	 */
	public function setPropertyType($propertyType) {
		$this->propertyType = $propertyType;
	}

	public function setFormBuilder($formBuilder) {
		$this->formBuilder = $formBuilder;
	}

	public function setPropertyValue($propertyValue) {
		$this->propertyValue = $propertyValue;
	}

	public function setPropertySchema($propertySchema) {
		$this->propertySchema = $propertySchema;
	}

	/**
	 * Evaluate the collection nodes
	 *
	 * @return string
	 */
	public function evaluate() {
		$parentFormElement = $this->tsValue('parentFormElement');
		if (!($parentFormElement instanceof \TYPO3\Form\Core\Model\AbstractSection)) {
			throw new \InvalidArgumentException('TODO: parent form element must be a section-like element');
		}

		$annotations = $this->tsValue('propertyAnnotations');
		if (isset($annotations['TYPO3\Expose\Annotations\Ignore'])) {
			return NULL;
		}

		$element = $parentFormElement->createElement($this->tsValue('identifier'), $this->tsValue('formFieldType'));

		if (method_exists($element, 'setPropertySchema')) {
			$element->setPropertySchema($this->tsValue('propertySchema'));
		}

		if (method_exists($element, 'setFormBuilder')) {
			$element->setFormBuilder($this->tsValue('formBuilder'));
		}

		$propertySchema = $this->tsValue('propertySchema');

		$element->setLabel($propertySchema['label']);
		$element->setDefaultValue($this->tsValue('propertyValue'));
		$element->setProperty('propertyName', $this->tsValue('propertyName'));

		$dataType = $propertySchema['type'];
		if (isset($propertySchema['elementType']) && $propertySchema['elementType'] !== NULL) {
			$dataType .= '<' . $propertySchema['elementType'] . '>';
		}
		$element->setDataType($dataType);

		return $element;
	}
}
?>