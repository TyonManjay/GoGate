<?php

namespace Exam\DomainBundle\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Domain\Entity;

/**
 * @ORM\MappedSuperClass
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
        $this->username = $username;
        $this->password = md5($password);
    }
}