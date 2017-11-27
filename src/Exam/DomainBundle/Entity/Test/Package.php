<?php

namespace Exam\DomainBundle\Entity\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;
use Exam\DomainBundle\Entity\Test\Question;
use Exam\DomainBundle\Entity\User\Participant;

/**
 * @ORM\Entity
 */
class Package extends Entity {
    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Question", cascade={"persist"})
     */
    private $questions;

    public function __construct($name) {
        parent::__construct();
        $this->name = $name;
        $this->questions = new ArrayCollection();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function addQuestion(Question $question) {
        $this->questions->add($question);
    }

    public function removeQuestion(Question $question) {
        $this->questions->removeElement($question);
    }

    public function getTotalQuestions() {
        return count($this->questions);
    }

    public function __toString() {
        return $this->name;
    }
}