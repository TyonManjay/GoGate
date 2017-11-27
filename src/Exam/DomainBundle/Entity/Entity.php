<?php

namespace Exam\DomainBundle\Entity;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $removedOn;

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

    public function update() {
        $this->updatedOn = new \DateTime('NOW');

        return $this;
    }

    public function getRemovedOn() {
        return $this->removedOn;
    }
}
