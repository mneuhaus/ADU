<?php
namespace Famelo\ADU\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Famelo.ADU".            *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Customer
 *
 * @Flow\Entity
 */
class Customer {

	/**
	 * The name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The name
	 *
	 * @var string
	 */
	protected $object;

	/**
	 * The type of the customer N, A, K, ''
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * The cycle
	 *
	 * @var integer
	 */
	protected $cycle = 30;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $cycleStart;

	/**
	 * The contact
	 *
	 * @var \Famelo\ADU\Domain\Model\Contact
	 * @ORM\ManyToOne(inversedBy="customers", cascade={"persist"})
	 * @Flow\Lazy
	 */
	protected $contact;

	/**
	 * The alternative contact
	 *
	 * @var \Famelo\ADU\Domain\Model\Contact
	 * @ORM\ManyToOne(inversedBy="alternative_customers", cascade={"persist"})
	 * @Flow\Lazy
	 */
	protected $alternativeContact;

	/**
	 * The category
	 *
	 * @var \Famelo\ADU\Domain\Model\Category
	 * @ORM\ManyToOne(inversedBy="customers")
	 * @Flow\Lazy
	 */
	protected $category;

	/**
	 * The consultant
	 *
	 * @var \Famelo\ADU\Domain\Model\User
	 * @ORM\ManyToOne(inversedBy="customers", cascade={"persist"})
	 * @ORM\joinColumn(onDelete="SET NULL")
	 * @Flow\Lazy
	 */
	protected $consultant;

	/**
	 * The Branch
	 *
	 * @var \Famelo\ADU\Domain\Model\Branch
	 * @ORM\ManyToOne(inversedBy="customers", cascade={"persist"})
	 * @Flow\Lazy
	 */
	protected $branch;

	/**
	 * The surveys
	 *
	 * @var \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Survey>
	 * @ORM\OneToMany(mappedBy="customer", cascade={"persist"})
	 * @ORM\OrderBy({"created" = "DESC"})
	 * @Flow\Lazy
	 */
	protected $surveys;

	/**
	 * The branch
	 * @var \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Rating>
	 * @ORM\OneToMany(targetEntity="\Famelo\ADU\Domain\Model\Rating", mappedBy="customer", cascade={"all"})
	 * @ORM\OrderBy({"created" = "DESC"})
	 * @Flow\Lazy
	 */
	protected $ratings;

	/**
	 * The created
	 * @var \DateTime
	 */
	protected $created;

	/**
	 * The created
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $termination = NULL;

	/**
	 * Tender if set
	 * @var \DateTime
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $tender = NULL;

	/**
	 * The selfEvaluationResult
	 *
	 * @var float
	 */
	protected $selfEvaluationResult = 0;

	/**
	 * The satisfaction ( higher = better )
	 *
	 * @var float
	 */
	protected $satisfaction = 0;

	public function __construct() {
		$this->created = new \DateTime();
		//$this->cycleStart = new \DateTime();
		$this->surveys = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function __toString() {
		if (strlen($this->getObject()) > 0) {
			return $this->getName() . chr(10) . ' (' . $this->getObject() . ')';
		}
		return $this->getName();
	}

	public function getHtml() {
		if (strlen($this->getObject()) > 0) {
			return '<b>' . $this->getName() . '</b><br />' . $this->getObject();
		}
		return '<b>' . $this->getName() . '</b>';
	}

	public function getIdentity() {
		return $this->Persistence_Object_Identifier;
	}

	/**
	 * Get the Customer's name
	 *
	 * @return string The Customer's name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets this Customer's name
	 *
	 * @param string $name The Customer's name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * Get the Customer's type (A, N, K, '')
	 *
	 * @return string The Customer's type
	 */
	public function getType() {
		if (empty($this->type) && $this->getTermination() !== NULL) {
			return 'K';
		}
		return $this->type;
	}

	/**
	 * Sets this Customer's type (A, N, K, '')
	 *
	 * @param string $name The Customer's type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get the Customer's cycle
	 *
	 * @return integer The Customer's cycle
	 */
	public function getCycle() {
		return $this->cycle;
	}

	/**
	 * Sets this Customer's cycle
	 *
	 * @param integer $cycle The Customer's cycle
	 * @return void
	 */
	public function setCycle($cycle) {
		$this->cycle = $cycle;
	}

	/**
	 * Get the Customer's contact
	 *
	 * @return \Famelo\ADU\Domain\Model\Contact The Customer's contact
	 */
	public function getContact() {
		return $this->contact;
	}

	/**
	 * Sets this Customer's contact
	 *
	 * @param \Famelo\ADU\Domain\Model\Contact $contact The Customer's contact
	 * @return void
	 */
	public function setContact($contact) {
		$this->contact = $contact;
	}

	/**
	 * Get the Customer's alternative contact
	 *
	 * @return \Famelo\ADU\Domain\Model\Contact The Customer's alternative contact
	 */
	public function getAlternativeContact() {
		return $this->alternativeContact;
	}

	/**
	 * Sets this Customer's alternative contact
	 *
	 * @param \Famelo\ADU\Domain\Model\Contact $alternativeContact The Customer's alternative contact
	 * @return void
	 */
	public function setAlternativeContact($alternativeContact) {
		$this->alternativeContact = $alternativeContact;
	}

	/**
	 * Get the Customer's category
	 *
	 * @return \Famelo\ADU\Domain\Model\Category The Customer's category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Sets this Customer's category
	 *
	 * @param \Famelo\ADU\Domain\Model\Category $category The Customer's category
	 * @return void
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 * Get the Customer's consultant
	 *
	 * @return \Famelo\ADU\Domain\Model\Consultant The Customer's consultant
	 */
	public function getConsultant() {
		return $this->consultant;
	}

	/**
	 * Sets this Customer's consultant
	 *
	 * @param \Famelo\ADU\Domain\Model\User $consultant The Customer's consultant
	 * @return void
	 */
	public function setConsultant($consultant) {
		$this->consultant = $consultant;
	}

	/**
	 * Get the Customer's branch
	 *
	 * @return \Famelo\ADU\Domain\Model\Branch The Customer's branch
	 */
	public function getBranch() {
		if ($this->branch === NULL && $this->consultant !== NULL) {
			return $this->consultant->getBranch();
		}
		return $this->branch;
	}

	/**
	 * Sets this Customer's branch
	 *
	 * @param \Famelo\ADU\Domain\Model\Branch $branch The Customer's branch
	 * @return void
	 */
	public function setBranch($branch) {
		$this->branch = $branch;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Survey> $surveys
	 */
	public function setSurveys($surveys) {
		$this->surveys = $surveys;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Survey>
	 */
	public function getSurveys() {
		return $this->surveys;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Rating> $ratings
	 */
	public function setRatings($ratings) {
		$this->ratings = $ratings;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Famelo\ADU\Domain\Model\Rating>
	 */
	public function getRatings() {
		return $this->ratings;
	}

	/**
	 * @param \Famelo\ADU\Domain\Model\Rating $rating
	 */
	public function addRating($rating) {
		$this->ratings->add($rating);
	}

	/**
	 * @param string $object
	 */
	public function setObject($object) {
		$this->object = $object;
	}

	/**
	 * @return string
	 */
	public function getObject() {
		return $this->object;
	}

	/**
	 * @param \DateTime $cycleStart
	 */
	public function setCycleStart($cycleStart) {
		$this->cycleStart = $cycleStart;
	}

	/**
	 * @return \DateTime
	 */
	public function getCycleStart() {
		if (is_object($this->cycleStart) && intval($this->cycleStart->format('Y')) > 0) {
			return $this->cycleStart;
		}

		return $this->getCreated();
	}

	/**
	 * @param float $satisfaction
	 */
	public function setSatisfaction($satisfaction) {
		$this->satisfaction = $satisfaction;
	}

	/**
	 * @return float
	 */
	public function getSatisfaction() {
		return $this->satisfaction;
	}

	/**
	 * @param float $selfEvaluationResult
	 */
	public function setSelfEvaluationResult($selfEvaluationResult) {
		$this->selfEvaluationResult = $selfEvaluationResult;
	}

	/**
	 * @return float
	 */
	public function getSelfEvaluationResult() {
		return $this->selfEvaluationResult;
	}

	public function getLatestSurvey() {
		if ($this->getSurveys()->count() > 0) {
			return $this->getSurveys()->first();
		}
		return NULL;
	}

	public function calculateSelfEvaluationResult() {

		$rating = $this->getLatestRating();
		$level = 1;

		if ($rating instanceof \Famelo\ADU\Domain\Model\Rating) {
			$level = $rating->getLevel();
		}

		if ($this->getIsNew()) {
			$level = 3.9;

		} elseif ($this->getIsTender()) {
			$level = 3.8;

		} elseif ($this->getIsTerminated()) {
			$level = 3.7;

		}

		$this->setSelfEvaluationResult($level);
	}

	public function getLatestRating() {
		// var_dump($this->getRatings()->count());
		return $this->getRatings()->first();
	}

	public function getRatingForThisWeek() {
		if (!$this->getActive()) {
			$rating = new \Famelo\ADU\Domain\Model\Rating();

			if     ($this->getIsNew())    $rating->setLevel(6);
			elseif ($this->getIsTender()) $rating->setLevel(5);
			else                          $rating->setLevel(4);

			return $rating;
		}
		if ($this->ratings->count() > 0) {
			$currentWeek = intval(date('W'));
			$year = intval(date('Y'));
			foreach ($this->ratings as $rating) {
				$ratingWeek = intval($rating->getCreated()->format('W'));
				$ratingYear = intval($rating->getCreated()->format('Y'));
				if ($currentWeek == $ratingWeek && $year == $ratingYear) {
					return $rating;
				}
			}
		}
		return NULL;
	}

	public function getRatingForLastWeek() {
		if (!$this->getActive()) {
			$rating = new \Famelo\ADU\Domain\Model\Rating();
			$rating->setLevel(4);
			return $rating;
		}
		if ($this->ratings->count() > 0) {
			$currentWeek = intval(date('W')) - 1;
			$year = intval(date('Y'));
			foreach ($this->ratings as $rating) {
				$ratingWeek = intval($rating->getCreated()->format('W'));
				$ratingYear = intval($rating->getCreated()->format('Y'));
				if ($currentWeek == $ratingWeek && $year == $ratingYear) {
					return $rating;
				}
			}
		}
		return NULL;
	}



	public function getRatingForTwoWeeksAgo() {
		return $this->getRatingForXWeeksAgo(2);
	}

	public function getRatingForThreeWeeksAgo() {
		return $this->getRatingForXWeeksAgo(3);
	}

	public function getRatingForFourWeeksAgo() {
		return $this->getRatingForXWeeksAgo(4);
	}

	public function getRatingForFiveWeeksAgo() {
		return $this->getRatingForXWeeksAgo(5);
	}

	public function getRatingForSixWeeksAgo() {
		return $this->getRatingForXWeeksAgo(6);
	}

	public function getRatingForSevenWeeksAgo() {
		return $this->getRatingForXWeeksAgo(7);
	}

	public function getRatingForEightWeeksAgo() {
		return $this->getRatingForXWeeksAgo(8);
	}


    public function getRatingImagesForThisYear() {
        $ratingsYear = array();
        for($w=1;$w<=52;$w++) {
            $ratingsYear[] = $this->getRatingImageForWeekAndYear($w,date('Y'));
        }
        return $ratingsYear;
    }

    public function getRatingImagesForLastYear() {
        $ratingsYear = array();
        for($w=1;$w<=52;$w++) {
            $ratingsYear[] = $this->getRatingImageForWeekAndYear($w,(int)date('Y')-1);
        }
        return $ratingsYear;
    }

    public function getRatingImagesFor2LastYears() {
        $ratingsYear = array();
        for($w=1;$w<=52;$w++) {
            $ratingsYear[] = $this->getRatingImageForWeekAndYear($w,(int)date('Y')-2);
        }
        return $ratingsYear;
    }

    public function getRatingForThisYear() {
        $ratingsYear = array();
        for($w=1;$w<=52;$w++) {
            $ratingsYear[] = $this->getRatingForWeekAndYear($w,date('Y'));
        }
        return $ratingsYear;
    }

    public function getRatingForLastYear() {
        $ratingsYear = array();
        for($w=1;$w<=52;$w++) {
            $ratingsYear[] = $this->getRatingForWeekAndYear($w,(int)date('Y')-1);
        }
        return $ratingsYear;
    }

    public function getRatingFor2LastYears() {
        $ratingsYear = array();
        for($w=1;$w<=52;$w++) {
            $ratingsYear[] = $this->getRatingForWeekAndYear($w,(int)date('Y')-2);
        }
        return $ratingsYear;
    }

    /**
     * @param integer $week
     * @param integer $year
     */
    public function getRatingImageForWeekAndYear($week, $year) {
        $rating = $this->getRatingForWeekAndYear($week, $year);
        if ($rating instanceof \Famelo\ADU\Domain\Model\Rating) {
            $color = $rating->getColor();
        } else {
            $color = 'white';
        }

        $image = 'img/Button-' . ucfirst($color) . '.png';
        return $image;
    }


    /**
     * @param integer $week
     * @param integer $year
     */
    public function getRatingForWeekAndYear($week,$year) {
        if (!$this->getActive()) {
            $rating = new \Famelo\ADU\Domain\Model\Rating();
            $rating->setLevel(4);
            return $rating;
        }
        if ($this->ratings->count() > 0) {
            foreach ($this->ratings as $rating) {
                $ratingWeek = intval($rating->getCreated()->format('W'));
                $ratingYear = intval($rating->getCreated()->format('Y'));
                if ($week == $ratingWeek && $year == $ratingYear) {
                    return $rating;
                }
            }
        }
        return NULL;
    }



    /**
	 * @param integer $x
	 */
	public function getRatingForXWeeksAgo($x = 2) {
		if (!$this->getActive()) {
			$rating = new \Famelo\ADU\Domain\Model\Rating();
			$rating->setLevel(4);
			return $rating;
		}
		if ($this->ratings->count() > 0) {
			$currentWeek = intval(date('W')) - (int)$x;
			$year = intval(date('Y'));
			foreach ($this->ratings as $rating) {
				$ratingWeek = intval($rating->getCreated()->format('W'));
				$ratingYear = intval($rating->getCreated()->format('Y'));
				if ($currentWeek == $ratingWeek && $year == $ratingYear) {
					return $rating;
				}
			}
		}
		return NULL;
	}


	public function getCurrentSurveyColor() {
		if (is_object($this->getLatestSurvey())) {
			return $this->getLatestSurvey()->getResultColor();
		}
		return 'white';
	}

	public function getCurrentRatingColor() {
		if (is_object($this->getLatestRating())) {
			return $this->getLatestRating()->getColor();
		}
		return 'white';
	}

	public function getCurrentRatingImage() {
		$color = $this->getCurrentRatingColor();
		$image = 'img/Button-' . ucfirst($color) . '.png';
		return $image;
	}

	public function getRatingImageForThisWeek() {
		$rating = $this->getRatingForThisWeek();
		if ($rating instanceof \Famelo\ADU\Domain\Model\Rating) {
			$color = $rating->getColor();
		} else {
			$color = 'white';
		}
		$survey = $this->getLatestSurvey();
		if ($survey instanceof \Famelo\ADU\Domain\Model\Survey) {
			$surveyColor = $survey->getResultColor();
			if ($surveyColor == 'orange' && $color !== 'red') {
				$color = $surveyColor;
			}
			if ($surveyColor == 'red') {
				$color = $surveyColor;
			}
		}
		$image = 'img/Button-' . ucfirst($color) . '.png';
		return $image;
	}

	public function getRatingImageForLastWeek() {
		$rating = $this->getRatingForLastWeek();
		if ($rating instanceof \Famelo\ADU\Domain\Model\Rating) {
			$color = $rating->getColor();
		} else {
			$color = 'white';
		}
		$image = 'img/Button-' . ucfirst($color) . '.png';
		return $image;
	}

	public function getRatingImageForTwoWeeksAgo() {
		$rating = $this->getRatingForTwoWeeksAgo();
		if ($rating instanceof \Famelo\ADU\Domain\Model\Rating) {
			$color = $rating->getColor();
		} else {
			$color = 'white';
		}
		$image = 'img/Button-' . ucfirst($color) . '.png';
		return $image;
	}

	/**
	 * @param integer $x
	 */
	public function getRatingImageForXWeeksAgo($x=2) {
		$rating = $this->getRatingForXWeeksAgo($x);
		if ($rating instanceof \Famelo\ADU\Domain\Model\Rating) {
			$color = $rating->getColor();
		} else {
			$color = 'white';
		}
		$image = 'img/Button-' . ucfirst($color) . '.png';
		return $image;
	}

	public function getRatingImageForThreeWeeksAgo() {
		return $this->getRatingImageForXWeeksAgo(3);
	}

	public function getRatingImageForFourWeeksAgo() {
		return $this->getRatingImageForXWeeksAgo(4);
	}

	public function getRatingImageForFiveWeeksAgo() {
		return $this->getRatingImageForXWeeksAgo(5);
	}

	public function getRatingImageForSixWeeksAgo() {
		return $this->getRatingImageForXWeeksAgo(6);
	}

	public function getRatingImageForSevenWeeksAgo() {
		return $this->getRatingImageForXWeeksAgo(7);
	}

	public function getRatingImageForEightWeeksAgo() {
		return $this->getRatingImageForXWeeksAgo(8);
	}

	/**
	 * @param DateTime $created
	 */
	public function setCreated($created) {
		$this->created = $created;
	}

	/**
	 * @return DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @param DateTime $termination
	 */
	public function setTermination($termination) {
		$this->termination = $termination;
	}

	/**
	 * @param DateTime $tender
	 */
	public function setTender($tender) {
		$this->tender = $tender;
	}

	/**
	 * @return DateTime
	 */
	public function getTermination() {
		if ($this->termination instanceof \DateTime && $this->termination->getTimestamp() > -62169987600) {
			return $this->termination;
		}
	}

	/**
	 * @return DateTime
	 */
	public function getTender() {
		if ($this->tender instanceof \DateTime && $this->tender->getTimestamp() > -62169987600) {
			return $this->tender;
		}
	}

	/**
	 * @return Boolean
	 */
	public function getIsTerminated() {
		return ($this->getType() === 'K');

		//return $this->getTermination() !== NULL;
	}

	/**
	 * @return Boolean
	 */
	public function getIsTender() {
		return ($this->getType() === 'A');

		/*
		if ($this->getTender() !== NULL) {
			$now = new \DateTime();
			return ($this->getTender() >= $now);

		} else {
			return false;
		}
		* */
	}

	/**
	 * @return Boolean
	 */
	public function getIsNew() {
		//$now = new \DateTime();
		//return $this->getCycleStart()->diff($now)->format('%a') <= 42;
		return ($this->getType() === 'N');
	}

	public function isSatisfied() {
		if ($this->getIsTerminated()) {
			return TRUE;
		}

		if ($this->getIsNew()) {
			return TRUE;
		}

		if (is_object($this->getLatestRating()) && $this->getLatestRating()->getLevel() > 1) {
			return FALSE;
		}

		if (is_object($this->getLatestSurvey()) && $this->getLatestSurvey()->getResult() < 5) {
			return FALSE;
		}

		return TRUE;
	}

	public function getMarker() {
		if ($this->getType() !== '') {
			return $this->getType();
		}
		return FALSE;

/*
		if ($this->getTermination() !== NULL) {
			return 'K';
		}
		if ($this->getIsNew()) {
			return 'N';
		}
		if ($this->getIsTender()) {
			return 'A';
		}
		return FALSE;
*/
	}

	public function getRatingSum() {
		$thisWeek = $this->getRatingForThisWeek();
		$values = array();
		if ($thisWeek instanceof \Famelo\ADU\Domain\Model\Rating) {
			if ($this->getIsNew()) $p = 1.4;
			elseif ($this->getIsTender()) $p = 1.6;
			elseif ($this->getIsTerminated()) $p = 1.8;
			else $p=1;

			$values[] = $this->getSelfEvaluationResult() / $p;
		}
		$survey = $this->getLatestSurvey();
		if ($survey instanceof \Famelo\ADU\Domain\Model\Survey) {
			$values[] = $survey->getResult() * 4;
			if ($survey->getResult() > 0.5) {
				$values[] = $survey->getResult() * 4;
			}
		}
		if (count($values) > 0) {
			return ( array_sum($values) / count($values) ) * 10;
		}
		return 0.0001;
	}

	public function getSurveysForReporting() {
		$surveys = array(
			intval(date('W')) => FALSE,
			intval(date('W')) - 1 => FALSE,
			intval(date('W')) - 2 => FALSE,
		);
		if ($this->surveys->count() > 0) {
			foreach ($this->surveys as $survey) {
				$week = intval($survey->getCreated()->format('W'));
				if (isset($surveys[$week])) {
					$surveys[$week] = $survey;
				}
			}
		}
		return $surveys;
	}


    public function getSurveysForReview() {

        $surveys = array();

        $currentYear = date('Y');
        for($year=$currentYear;$year>=($currentYear-3);$year--) {
            for($week=1;$week<=52;$week++) {
                $surveys[$year][$week] = FALSE;
            }
        }

        if ($this->surveys->count() > 0) {
            foreach ($this->surveys as $survey) {
                $year = intval($survey->getCreated()->format('Y'));
                $week = intval($survey->getCreated()->format('W'));

                $surveys[$year][$week] = $survey;
            }
        }
        return $surveys;
    }

    public function getSurveysForCurrentYear() {
        $surveys = array();
        for($week=1;$week<=52;$week++) {
            $surveys[$week] = FALSE;
        }

        if ($this->surveys->count() > 0) {
            foreach ($this->surveys as $survey) {
                $year = intval($survey->getCreated()->format('Y'));
                $week = intval($survey->getCreated()->format('W'));

                if (isset($surveys[$week]) && $year==date('Y')) {
                    $surveys[$week] = $survey;

                }
            }

        }
        return $surveys;
    }


    public function getSurveysForLastYear() {
        $surveys = array();
        for($week=1;$week<=52;$week++) {
            $surveys[$week] = FALSE;
        }

        if ($this->surveys->count() > 0) {
            foreach ($this->surveys as $survey) {
                $year = intval($survey->getCreated()->format('Y'));
                $week = intval($survey->getCreated()->format('W'));

                if (isset($surveys[$week]) && (int)$year==((int)date('Y')-1)) {
                    $surveys[$week] = $survey;

                }
            }

        }
        return $surveys;
    }


    public function getSurveysForLast2Years() {
        $surveys = array();
        for($week=1;$week<=52;$week++) {
            $surveys[$week] = FALSE;
        }

        if ($this->surveys->count() > 0) {
            foreach ($this->surveys as $survey) {
                $year = intval($survey->getCreated()->format('Y'));
                $week = intval($survey->getCreated()->format('W'));

                if (isset($surveys[$week]) && (int)$year==(int)(date('Y')-2)) {
                    $surveys[$week] = $survey;

                }
            }

        }
        return $surveys;
    }


	public function getActive() {
		if (!$this->getIsTerminated() && !$this->getIsNew() && !$this->getIsTender()) {
			return TRUE;
		}

		return FALSE;
	}


}
?>
