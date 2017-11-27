<?php

namespace Exam\DomainBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

class Schemator extends SchemaTool {
    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
        parent::__construct($this->em);
    }

    public function refreshSchema(){
        $classes = $this->em->getMetadataFactory()->getAllMetadata();
        parent::updateSchema($classes, true);
    }
}