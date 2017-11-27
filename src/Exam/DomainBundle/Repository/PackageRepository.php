<?php

namespace Exam\DomainBundle\Repository;

use Doctrine\ORM\EntityManager;
use Exam\DomainBundle\Repository\BaseRepository;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Service("packageRepo")
 */
class PackageRepository extends BaseRepository {
    /**
     * @InjectParams({
     *      "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em) {
        parent::__construct($em);
    }
}