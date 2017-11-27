<?php

namespace Exam\DomainBundle\Entity\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;
use Exam\DomainBundle\Entity\Test\Option;

/**
 * @ORM\Entity
 */
class Question extends Entity {
    /**
     * @ORM\ManyToMany(targetEntity="Option", cascade={"persist"}, inversedBy="question")
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity="Option")
     */
    private $answer;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function __construct($description) {
        parent::__construct();
        $this->options = new ArrayCollection();

        $this->description = $description;
    }

    public function getOptions() {
        return $this->options;
    }

    public function addOption(Option $option) {
        $this->options->add($option);
    }

    public function removeOption(Option $option) {
        $this->options->remove($option);
    }

    public function setAnswer($optionId) {
        $foundOptions = $this->options->filter(function(Option $option) use($optionId) {
            return $option->getId() == $optionId;
        });

        $this->answer = $foundOptions->first();

        return $this;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }
}