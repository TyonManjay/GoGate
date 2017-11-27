<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quantum
 * Date: 2/18/14
 * Time: 5:24 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Exam\WebBundle\Service;


use Doctrine\ORM\EntityManager;
use Exam\DomainBundle\Entity\Entity;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class CRUDService
 * @package Exam\WebBundle\Service
 * @Service("CRUDService")
 */
class CRUDService {
    private $em;

    /**
     * @InjectParams({
     *      "em" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function update(Entity $entity) {
        $this->em->persist($entity);
    }

    public function save(Entity $entity = null) {
        $this->em->flush($entity);
    }
}