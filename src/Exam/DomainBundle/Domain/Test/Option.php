<?php

namespace Exam\DomainBundle\Domain\Test;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Option {
    /**
     * @ORM\OneToOne(targetEntity="Question")
     */
    private $question;

    /**
     * @ORM\Column(type="text")
     */
    private $description;
}