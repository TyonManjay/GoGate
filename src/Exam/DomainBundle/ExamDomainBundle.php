<?php

namespace Exam\DomainBundle;

use Exam\DomainBundle\Naming\MySqlNamingStrategy;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExamDomainBundle extends Bundle {
    public function boot() {
        $this->container->get('doctrine')
            ->getManager()
            ->getConfiguration()
            ->setNamingStrategy(new MySqlNamingStrategy('exam_'));
    }
}