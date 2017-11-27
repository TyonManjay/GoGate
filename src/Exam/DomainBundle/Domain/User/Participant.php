<?php

namespace Exam\DomainBundle\Domain\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Participant extends User {
    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}