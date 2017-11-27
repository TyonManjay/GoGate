<?php

namespace Exam\DomainBundle\Entity\Test;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;

/**
 * @ORM\Entity
 */
class Option extends Entity {
    /**
     * @ORM\ManyToMany(targetEntity="Question", mappedBy="options")
     */
    private $question;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function __construct($description) {
        parent::__construct();
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function __toString() {
        return $this->description;
    }
}