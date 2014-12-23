<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Branch
 *
 * @Flow\Entity
 */
class Branch {

	/**
	 * The name
	 * @var string
	 */
	protected $name;

	/**
	 * The branch
	 * @var \Famelo\ADU\Domain\Model\Branch
	 * @ORM\ManyToOne(inversedBy="sub_branches", cascade={"all"})
	 * @ORM\JoinColumn(name="parent_branch_id")
	 * @Flow\Lazy
	 */
	protected $branch;

	/**
	 * The branch
	 * @var \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Branch>
	 * @ORM\OneToMany(targetEntity="\Famelo\ADU\Domain\Model\Branch", mappedBy="branch")
	 * @Flow\Lazy
	 */
	protected $children;

	/**
	 * The questions
	 * @var \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Question>
	 * @ORM\ManyToMany(inversedBy="branches", cascade={"all"})
	 */
	protected $questions;

	public function __toString() {
		return $this->getName();
	}

	/**
	 * Get the branch's name
	 *
	 * @return string The branch's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this Branch's name
	 *
	 * @param string $name The Branch's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get the branch's branch
	 *
	 * @return branch The branch's branch
	 */
	public function getBranch() {
		return $this->branch;
	}

	/**
	 * Sets this branch's branch
	 *
	 * @param branch $branch The branch's branch
	 * @return void
	 */
	public function setBranch($branch) {
		$this->branch = $branch;
	}

	/**
	 * Get the branch's questions
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Question> The branch's questions
	 */
	public function getQuestions() {
		return $this->questions;
	}

	/**
	 * Get the branch's questions
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Question> The branch's questions
	 */
	public function getMatchingQuestions() {
		if (count($this->questions) > 0) {
			return $this->questions;
		}

		return $this->getBranch()->getMatchingQuestions();
	}

	/**
	 * Sets this branch's questions
	 *
	 * @param \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Question> $questions The branch's questions
	 * @return void
	 */
	public function setQuestions($questions) {
		$this->questions = $questions;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Branch> $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Branch>
	 */
	public function getChildren() {
		return $this->children;
	}

	public function getAllChildren() {
		$children = array();
		foreach ($this->children as $child) {
			$children = array_merge($children, $child->getAllChildren());
			$children[] = $child;
		}
		return $children;
	}
}
?>