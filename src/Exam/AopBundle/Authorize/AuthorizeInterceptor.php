<?php

namespace Exam\AopBundle\Authorize;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Service("authorize_interceptor")
 */
class AuthorizeInterceptor implements MethodInterceptorInterface {
    private $session;

    /**
     * @InjectParams
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    function intercept(MethodInvocation $invocation) {
        if(!$this->session->has('admin')) {
            $this->session->getFlashBag()->add('error', 'Not authorized. Please sign-in.');

            return new RedirectResponse('/admin/login');
        }

        return $invocation->proceed();
    }
}