<?php

namespace Exam\AopBundle\DBService\DBTransaction;

interface ITransaction {
    public function begin();
    public function commit();
    public function rollback();
    public function close();
}