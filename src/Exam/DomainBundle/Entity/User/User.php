<?php

namespace Exam\DomainBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;

/**
 * @ORM\Entity
 */
class User extends Entity {
    /**
     * @ORM\Column(type="text")
     */
    private $username;

    /**
     * @ORM\Column(type="text")
     */
    private $password;

    public function __construct($username, $password) {
        parent::__construct();
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($newPassword) {
        $this->password = md5($newPassword);

        return $this;
    }
}