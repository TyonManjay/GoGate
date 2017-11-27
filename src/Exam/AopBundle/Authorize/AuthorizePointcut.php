<?php

namespace Exam\AopBundle\Authorize;

use JMS\AopBundle\Aop\PointcutInterface;
use Doctrine\Common\Annotations\Reader;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service
 * @Tag("jms_aop.pointcut", attributes = {"interceptor" = "authorize_interceptor"})
 */
class AuthorizePointcut implements PointcutInterface
{
    private $reader;

    /**
     * @InjectParams({
     *      "reader" = @Inject("annotation_reader")
     * })
     */
    public function __construct(Reader $reader){
        $this->reader = $reader;
    }

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
        return $method->getDeclaringClass()->getNamespaceName() === 'Exam\AdminBundle\Controller'
               && $method->getDeclaringClass()->getShortName() !== 'LoginController';
    }
}