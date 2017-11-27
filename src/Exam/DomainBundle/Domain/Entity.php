<?php

namespace Exam\DomainBundle\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Entity {

    protected function __construct() {
        $this->createdOn = new \DateTime('NOW');
        $this->updatedOn = new \DateTime('NOW');
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdOn;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedOn;

    /**
     * Get id
     *
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get postedAt
     *
     * @return \DateTime
     */
    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getUpdatedOn() {
        return $this->updatedOn;
    }
}
