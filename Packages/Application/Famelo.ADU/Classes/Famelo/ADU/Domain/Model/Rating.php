<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Rating
 *
 * @Flow\Entity
 */
class Rating {

	/**
	 * The customer
	 * @var \Famelo\ADU\Domain\Model\Customer
	 * @ORM\ManyToOne(inversedBy="ratings", cascade={"all"})
	 * @Flow\Lazy
	 */
	protected $customer;

	/**
	 * The action
	 * @var string
	 */
	protected $action = '';

	/**
	 * The comment
	 * @var string
	 */
	protected $comment = '';

	/**
	 * The supervisorComment
	 * @var string
	 */
	protected $supervisorComment = '';

	/**
	 * The created
	 * @var \DateTime
	 */
	protected $created;

	/**
	 * The date
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * The level
	 * @var integer
	 */
	protected $level;

	/**
	* TODO: Document this Method! ( __construct )
	*/
	public function __construct() {
		$this->created = new \DateTime();
		$this->date = new \DateTime();
	}

	public function __toString() {
		return strval($this->level);
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * Get the Rating's comment
	 *
	 * @return string The Rating's comment
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * Sets this Rating's comment
	 *
	 * @param string $comment The Rating's comment
	 * @return void
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}

	/**
	 * Get the Rating's created
	 *
	 * @return \DateTime The Rating's created
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * Sets this Rating's created
	 *
	 * @param \DateTime $created The Rating's created
	 * @return void
	 */
	public function setCreated($created) {
		$this->created = $created;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * Get the Rating's level
	 *
	 * @return integer The Rating's level
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * Sets this Rating's level
	 *
	 * @param integer $level The Rating's level
	 * @return void
	 */
	public function setLevel($level) {
		$this->level = $level;
	}

	/**
	 * @param \Famelo\ADU\Domain\Model\Customer $customer
	 */
	public function setCustomer($customer) {
		$this->customer = $customer;
	}

	/**
	 * @return \Famelo\ADU\Domain\Model\Customer
	 */
	public function getCustomer() {
		return $this->customer;
	}

	public function getColor() {
		$colors = array(
			'1' => 'green',
			'2' => 'yellow',
			'3' => 'orange',
			'4' => 'red',
			'5' => 'red',
			'6' => 'red'

		);
		return $colors[$this->getLevel()];
	}

	public function getImage() {
		return 'img/Button-' . ucfirst($this->getColor()) . '.png';
	}

	/**
	 * @param string $supervisorComment
	 */
	public function setSupervisorComment($supervisorComment) {
		$this->supervisorComment = $supervisorComment;
	}

	/**
	 * @return string
	 */
	public function getSupervisorComment() {
		return $this->supervisorComment;
	}
}

?>
