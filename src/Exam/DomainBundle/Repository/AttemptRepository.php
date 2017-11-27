<?php

namespace Exam\DomainBundle\Repository;

use Doctrine\ORM\EntityManager;
use Exam\DomainBundle\Repository\BaseRepository;
use Exam\WebBundle\Service\LoginService;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("AttemptRepo")
 */
class AttemptRepository extends BaseRepository {

    /**
     * @InjectParams({
     *      "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em) {
        parent::__construct($em);
    }
}