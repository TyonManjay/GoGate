<?php

namespace Exam\AopBundle\DBService\DBTransaction;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("trx.mysql")
 */
class MysqlTrx extends Trx implements ITransaction
{
    /**
     * @InjectParams
     */
    public function __construct(Registry $doctrine) {
        parent::__construct($doctrine);
    }

    public function begin()
    {
        $this->manager->beginTransaction();
    }

    public function commit()
    {
        $this->manager->flush();
        $this->manager->commit();
    }

    public function rollback()
    {
        $this->manager->rollback();
    }

    public function close()
    {
        $this->manager->close();
    }
}