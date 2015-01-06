<?php
namespace Famelo\ADU\ViewHelpers\Form;


use TYPO3\Flow\Annotations as Flow;

/**
 */
class AdvancedSelectViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper {

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('defaultOption', 'string', 'value to prepend', FALSE);
        $this->registerArgument('addDefaultOption', 'boolean', 'value to prepend', FALSE);
        $this->registerArgument('addSpecialOptionsYears', 'boolean', 'add special options years', FALSE);
        $this->registerArgument('specialOptionsYearsLabel', 'string', 'label for special options years', FALSE);

	}

	protected function getOptions() {
        $output = parent::getOptions();

        $specialOptionsYears = array(
            'currentYear' => $this->arguments['specialOptionsYearsLabel'] .' '. date('Y'),
            'lastYear'  => $this->arguments['specialOptionsYearsLabel'] .' '. date('Y',strtotime('-1 year')),
            '-2years' => $this->arguments['specialOptionsYearsLabel'] .' '. date('Y',strtotime('-2 years')),
        );
        if ($this->arguments['addDefaultOption'])       $output = array_merge(array("" => $this->arguments['defaultOption']), $output);
        if ($this->arguments['addSpecialOptionsYears']) $output = array_merge($output, $specialOptionsYears);

        // [patch] replace empty option fields with defaultOption, so that standard option field is not empty if the option list contains more than one empty entrys
        $output = array_replace($output,array("" => $this->arguments['defaultOption']));

        return $output;
        //return array("" => $this->arguments['defaultOption']) + parent::getOptions() + (($this->arguments['addSpecialOptionsYears']) ? $specialOptionsYears : '');

	}
}

?>
