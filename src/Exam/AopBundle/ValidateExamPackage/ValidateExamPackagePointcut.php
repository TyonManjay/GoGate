<?php

namespace Exam\AopBundle\ValidateExamPackage;

use JMS\AopBundle\Aop\PointcutInterface;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * Class ValidateExamPackagePointcut
 * @package Exam\AopBundle
 * @Service
 * @Tag("jms_aop.pointcut", attributes = {"interceptor" = "validate_interceptor"})
 */
class ValidateExamPackagePointcut implements PointcutInterface {

    /**
     * Determines whether the advice applies to instances of the given class.
     *
     * There are some limits as to what you can do in this method. Namely, you may
     * only base your decision on resources that are part of the ContainerBuilder.
     * Specifically, you may not use any data in the class itself, such as
     * annotations.
     *
     * @param  \ReflectionClass $class
     * @return boolean
     */
    public function matchesClass(\ReflectionClass $class)
    {
        return true;
    }

    /**
     * Determines whether the advice applies to the given method.
     *
     * This method is not limited in the way the matchesClass method is. It may
     * use information in the associated class to make its decision.
     *
     * @param  \ReflectionMethod $method
     * @return boolean
     */
    public function matchesMethod(\ReflectionMethod $method)
    {
        return $method->getDeclaringClass()->getNamespaceName() === 'Exam\WebBundle\Controller'
            and $method->getDeclaringClass()->getShortName() !== 'LoginController';
    }
}