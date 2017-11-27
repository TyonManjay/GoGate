<?php

namespace Exam\DomainBundle\Entity\Test;

use Doctrine\ORM\Mapping as ORM;
use Exam\DomainBundle\Entity\Entity;

/**
 * @ORM\Entity
 */
class Token extends Entity {
    /**
     * @ORM\Column(type="text")
     */
    private $unique;

    private $status;
}