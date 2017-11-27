<?php

namespace Exam\DomainBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;

/**
 * @ORM\Entity
 */
class Admin extends Entity {
    /**
     * @ORM\OneToOne(targetEntity="User")
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function __construct(User $user, $name) {
        parent::__construct();
        $this->user = $user;
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getUser() {
        return $this->user;
    }
}