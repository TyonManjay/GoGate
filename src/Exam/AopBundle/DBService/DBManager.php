<?php

namespace Exam\AopBundle\DBService;

use Exam\AopBundle\DBService\DBTransaction\ITransaction;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("dbmanager")
 */
class DBManager {
    private $trx,
            $lock = 0;

    /**
     * @InjectParams({
     *      "trx" = @Inject("trx.mysql")
     * })
     */
    public function __construct(ITransaction $trx){
        $this->trx = $trx;
    }

    public function begin(){
        ++$this->lock;
        return $this->trx->begin();
    }

    public function commit(){
        if(--$this->lock === 0){
            $this->trx->commit();
        }
    }

    public function rollback(){
        return ($this->trx->rollback() and $this->trx->close());
    }

}