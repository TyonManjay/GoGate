<?php

namespace Exam\DomainBundle\Domain\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Domain\Question;
use Exam\DomainBundle\Domain\User\Participant;

/**
 * @ORM\Entity
 */
class Package {
    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Question")
     */
    private $questions;

    /**
     * @ORM\ManyToMany(targetEntity="Participant")
     */
    private $participants;

    public function __construct($name) {
        $this->name = $name;
        $this->questions = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function addQuestion(Question $question) {
        $this->questions->add($question);
    }

    public function removeQuestion(Question $question) {
        $this->questions->removeElement($question);
    }

    public function addParticipant(Participant $participant) {
        $this->participants->add($participant);
    }

    public function removeParticipant(Participant $participant) {
        $this->participants->removeElement($participant);
    }
}