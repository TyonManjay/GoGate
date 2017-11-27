<?php

namespace Exam\AopBundle\DBService\DBTransaction;

use Doctrine\Bundle\DoctrineBundle\Registry;

abstract class Trx {
    protected $manager;

    public function __construct(Registry $doctrine) {
        $this->manager = $doctrine->getManager();
    }
}