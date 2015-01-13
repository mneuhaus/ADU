<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Answer
 *
 * @Flow\Entity
 */
class Answer {

	/**
	 * The survey
	 * @var \Famelo\ADU\Domain\Model\Survey
	 * @ORM\ManyToOne(inversedBy="answers", cascade={"all"})
	 */
	protected $survey;

	/**
	 * The question
	 * @var \Famelo\ADU\Domain\Model\Question
	 * @ORM\ManyToOne(inversedBy="answers", cascade={"all"})
	 */
	protected $question;

	/**
	 * The answer
	 * @var string
	 */
	protected $answer;

	/**
	 * The comment
	 * @var string
	 */
	protected $comment = '';

	/**
	 * The comment
	 * @var string
	 */
	protected $note = '';

	/**
	 * @var string
	 */
	protected $action = '';

	public function __toString() {
		if (is_object($this->question)) {
			return $this->question->getBody();
		} else {
			return '';
		}
	}

	public function getIdentifier() {
		return $this->Persistence_Object_Identifier;
	}

	/**
	 * Get the Answer's question
	 *
	 * @return \Famelo\ADU\Domain\Model\Question The Answer's question
	 */
	public function getQuestion() {
		return $this->question;
	}

	/**
	 * Sets this Answer's question
	 *
	 * @param \Famelo\ADU\Domain\Model\Question $question The Answer's question
	 * @return void
	 */
	public function setQuestion($question) {
		$this->question = $question;
	}

	/**
	 * Get the Answer's answer
	 *
	 * @return string The Answer's answer
	 */
	public function getAnswer() {
		return $this->answer;
	}

	/**
	 * Sets this Answer's answer
	 *
	 * @param string $answer The Answer's answer
	 * @return void
	 */
	public function setAnswer($answer) {
		$this->answer = $answer;
	}

	/**
	 * Get the Answer's comment
	 *
	 * @return string The Answer's comment
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * Sets this Answer's comment
	 *
	 * @param string $comment The Answer's comment
	 * @return void
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}

	/**
	 * @param \Famelo\ADU\Domain\Model\Survey $survey
	 */
	public function setSurvey($survey) {
		$this->survey = $survey;
	}

	/**
	 * @return \Famelo\ADU\Domain\Model\Survey
	 */
	public function getSurvey() {
		return $this->survey;
	}

	/**
	 * @param string $note
	 */
	public function setNote($note) {
		$this->note = $note;
	}

	/**
	 * @return string
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	public function getColor() {
		$colors = array(
			'green' => array(0, 0.25),
			'yellow' => array(0.25, 0.5),
			'orange' => array(0.5, 0.75),
			'red' => array(0.75, 1.1)
		);
		$result = $this->getAnswer() / 9;
		foreach ($colors as $color => $range) {
			if ($result >= $result && $result <= $range[1]) {
				return $color;
			}
		}
	}

	public function getImage() {
		return 'img/Button-' . ucfirst($this->getColor()) . '.png';
	}

	public function getNeedsAction() {
		return in_array($this->getColor(), array('orange', 'red'));
	}
}
?>