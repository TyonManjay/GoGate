<?php

namespace Exam\AopBundle\Transactional;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Doctrine\DBAL\DBALException;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Exam\AopBundle\DBService\DBManager;

/**
 * Class TransactionalInterceptor
 * @package Vibiz\AopBundle\Transactional
 * @Service("transactional_interceptor")
 */
class TransactionalInterceptor implements MethodInterceptorInterface{

    private $transaction;

    /**
     * @InjectParams({
     *      "transaction" = @Inject("dbmanager")
     * })
     */
    public function __construct(DBManager $transaction){
        $this->transaction = $transaction;
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
        try{
            $this->transaction->begin();

            $result = $invocation->proceed();
            
            $this->transaction->commit();

            return $result;
        }catch (\RuntimeException $e){

            $this->transaction->rollback();

            throw $e;
        }

    }
}