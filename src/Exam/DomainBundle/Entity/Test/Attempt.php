<?php

namespace Exam\DomainBundle\Entity\Test;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;
use Exam\DomainBundle\Entity\Test\Question;

/**
 * @ORM\Entity
 */
class Attempt extends Entity {
    /**
     * @ORM\ManyToOne(targetEntity="Enrollment", inversedBy="attempts")
     */
    private $enrollment;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="Option")
     */
    private $answer;

    /**
     * @ORM\Column(type="datetime")
     */
    private $answeredOn;

    public function __construct(Enrollment $enrollment, Question $question, Option $option) {
        parent::__construct();
        $this->question = $question;
        $this->answer = $option;
        $this->enrollment = $enrollment;
        $this->answeredOn = new \DateTime('NOW');
    }

    public function getQuestion() {
        return $this->question;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function getAnsweredOn() {
        return $this->answeredOn;
    }

    public function isCorrect() {
        return $this->answer === $this->question->getAnswer();
    }
}