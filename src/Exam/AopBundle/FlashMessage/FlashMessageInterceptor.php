<?php

namespace Exam\AopBundle\FlashMessage;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\Common\Annotations\Reader;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\DependencyInjection\Container;

/**
 * @Service("flashMessage_interceptor")
 */
class FlashMessageInterceptor implements MethodInterceptorInterface {
    private $reader,
            $container;

    /**
     * @InjectParams({
     *      "reader" = @Inject("annotation_reader"),
     *      "container" = @Inject("service_container")
     * })
     */
    public function __construct(Reader $reader, Container $container) {
        $this->reader = $reader;
        $this->container = $container;
    }

    /**
     * Called when intercepting a method call.
     *
     * @param MethodInvocation $invocation
     * @return mixed the return value for the method invocation
     * @throws \Exception may throw any exception
     */
    function intercept(MethodInvocation $invocation)
    {
        $params = (array)$this->reader->getMethodAnnotation($invocation->reflection, 'Exam\AopBundle\FlashMessage');

        try {

            $result = $invocation->proceed();

            $this->setFlashBag($params['key'], $params['success']);

            return $result;

        }catch (\RuntimeException $e){
            $this->setFlashBag($params['key'], $params['fail']);
        }
    }

    private function setFlashBag($key, array $message) {
        $bag = $this->container->get('session')->getFlashBag();

        if(count($bag->clear()) === 0){
            $bag->set($key, $message);
        }
    }
}