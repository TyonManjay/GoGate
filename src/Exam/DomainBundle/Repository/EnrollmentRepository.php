<?php

namespace Exam\DomainBundle\Repository;

use Doctrine\ORM\EntityManager;
use Exam\DomainBundle\Entity\Test\Package;
use Exam\DomainBundle\Entity\User\Participant;
use Exam\DomainBundle\Repository\BaseRepository;
use Exam\WebBundle\Service\LoginService;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Service("enrollmentRepo")
 */
class EnrollmentRepository extends BaseRepository {
    /**
     * @InjectParams({
     *      "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em) {
        parent::__construct($em);
    }

    public function findForPackage(Package $package) {
        return $this->findBy([
            'package' => $package
        ], [
            'createdOn' => 'desc'
        ]);
    }

    public function findForParticipants(Participant $participant) {
        return $this->findBy([
            'participant' => $participant
        ], [
            'createdOn' => 'desc'
        ]);
    }
}