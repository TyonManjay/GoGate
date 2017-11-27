<?php

namespace Exam\AdminBundle;

use Exam\AdminBundle\DependencyInjection\ExamAdminExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExamAdminBundle extends Bundle {
    public function __construct() {
        if(!defined('BUNDLE_SEPARATOR')) define('BUNDLE_SEPARATOR', ':');
    }

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->registerExtension(new ExamAdminExtension());
    }
}