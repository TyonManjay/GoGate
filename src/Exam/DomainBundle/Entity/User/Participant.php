<?php

namespace Exam\DomainBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;

/**
 * @ORM\Entity
 */
class Participant extends Entity {
    /**
     * @ORM\OneToOne(targetEntity="User")
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dob;

    /**
     * @ORM\Column(type="string")
     */
    private $registrationId;

    public function __construct(User $user, $registrationId, $name, $dob) {
        parent::__construct();
        $this->user = $user;
        $this->registrationId = $registrationId;
        $this->name = $name;
        $this->dob = $dob;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDoB() {
        return $this->dob;
    }

    public function setDoB($dob) {
        $this->dob = $dob;
    }

    public function getRegistrationId() {
        return $this->registrationId;
    }

    public function setRegistrationId($registrationId) {
        $this->registrationId = $registrationId;
    }

    public function __toString() {
        return $this->name;
    }
}