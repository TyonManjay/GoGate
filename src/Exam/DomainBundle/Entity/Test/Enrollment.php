<?php

namespace Exam\DomainBundle\Entity\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;
use Exam\DomainBundle\Entity\Test\Question;
use Exam\DomainBundle\Entity\User\Participant;
use Exam\WebBundle\Resources\globals\Config;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 */
class Enrollment extends Entity {
    /**
     * @ORM\ManyToOne(targetEntity="Exam\DomainBundle\Entity\User\Participant")
     */
    private $participant;

    /**
     * @ORM\ManyToOne(targetEntity="Package")
     */
    private $package;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startedOn;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishedOn;

    /**
     * @ORM\OneToMany(targetEntity="Attempt", mappedBy="enrollment", cascade={"persist"})
     */
    private $attempts;

    public function __construct(Participant $participant, Package $package) {
        parent::__construct();
        $this->participant = $participant;
        $this->package = $package;
        $this->attempts = new ArrayCollection();
    }

    public function addAttempts(Attempt $attempt) {
        $this->attempts->add($attempt);

        return $this;
    }

    public function start() {
        $this->startedOn = new \DateTime('NOW');

        return $this;
    }

    public function finish() {
        $this->finishedOn = new \DateTime('NOW');

        return $this;
    }

    public function restart() {
        $this->startedOn = new \DateTime('NOW');
        $this->finishedOn = null;
        $this->attempts = new ArrayCollection();

        return $this;
    }

    public function getStartedOn() {
        return $this->startedOn;
    }

    public function getFinishedOn() {
        return $this->finishedOn;
    }

    public function isStarted() {
        return isset($this->startedOn);
    }

    public function isFinished() {
        return isset($this->finishedOn);
    }

    public function getPackage() {
        return $this->package;
    }

    public function getParticipant() {
        return $this->participant;
    }

    public function getAttemptsFor($questionId) {
        return $this->attempts->filter(function(Attempt $attempt) use($questionId) {
            return $attempt->getQuestion()->getId() === $questionId;
        });
    }

    public function getCorrectAnswers() {
        return $this->package->getQuestions()->filter(function(Question $question) {
            return $this->getAttemptsFor($question->getId())->last()
                ? $this->getAttemptsFor($question->getId())->last()->getAnswer() === $question->getAnswer()
                : false;
        });
    }

    private function formatSeconds($secondsLeft) {

        $minuteInSeconds = 60;
        $hourInSeconds = $minuteInSeconds * 60;
        $dayInSeconds = $hourInSeconds * 24;

        $days = floor($secondsLeft / $dayInSeconds);
        $secondsLeft = $secondsLeft % $dayInSeconds;

        $hours = floor($secondsLeft / $hourInSeconds);
        $secondsLeft = $secondsLeft % $hourInSeconds;

        $minutes= floor($secondsLeft / $minuteInSeconds);

        $seconds = $secondsLeft % $minuteInSeconds;

        $timeComponents = array();

        if ($days > 0) {
            $timeComponents[] = $days . " day" . ($days > 1 ? "s" : "");
        }

        if ($hours > 0) {
            $timeComponents[] = $hours . " hour" . ($hours > 1 ? "s" : "");
        }

        if ($minutes > 0) {
            $timeComponents[] = $minutes . " minute" . ($minutes > 1 ? "s" : "");
        }

        if ($seconds > 0) {
            $timeComponents[] = $seconds . " second" . ($seconds > 1 ? "s" : "");
        }

        if (count($timeComponents) > 0) {
            $formattedTimeRemaining = implode(", ", $timeComponents);
            $formattedTimeRemaining = trim($formattedTimeRemaining);
        } else {
            $formattedTimeRemaining = "No time remaining.";
        }

        return $formattedTimeRemaining;
    }

    public function getTimeleft($format = true) {
        if($this->isStarted()) {
            $interval = $this->getStartedOn()->diff(new \DateTime('now'));

            $hours   = $interval->format('%h');
            $minutes = $interval->format('%i');
            $seconds = $interval->format('%s');

            $result = ((Config::TIMELIMIT - ($hours * 60 + $minutes))-1)*60-$seconds;

            if($format) return $this->formatSeconds($result);

            return $result;
        }

        return $this->formatSeconds(0);
    }

    /**
     * Return work time for an enrollment.
     * Will return 0 if enrollment is not started or finished
     * @param bool $inSeconds Return value (in seconds or in minutes), default to minutes
     * @return float|int
     */
    public function getTotalWorkTime($inSeconds = false) {
        if(!$this->isStarted() or !$this->isFinished()) {
            return 0;
        }

        $start = strtotime($this->startedOn);
        $finish = strtotime($this->finishedOn);

        $workTimeInSeconds = $finish - $start;

        return $inSeconds
            ? $workTimeInSeconds
            : $workTimeInSeconds / 60;
    }
}