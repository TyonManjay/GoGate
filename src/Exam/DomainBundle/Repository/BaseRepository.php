<?php

namespace Exam\DomainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Exam\DomainBundle\Repository\Schemator;
use Exam\DomainBundle\Entity\Entity;

abstract class BaseRepository extends EntityRepository {
    protected $em;

    public function __construct(EntityManager $em) {
        //(new Schemator($em))->refreshSchema();
        parent::__construct($em, new ClassMetadata(self::getClass()));
    }

    public function all($includeRemoved = false) {
        $criterion = [];





        if(!$includeRemoved) {
            $criterion ['removedOn'] = null;
        }

        return $this->findBy($criterion);
    }

    public function count(){
        return count($this->all());
    }

    public function persist(Entity $entity){
        $this->getManager()->persist($entity->update());

        return $entity;
    }

    public function remove(Entity $entity){
        $this->getManager()->remove($entity);
    }

    public function getManager(){
        return $this->getEntityManager();
    }

    function getDomainNamespaces() {
        return [
            'Exam\DomainBundle\Entity\\',
            'Exam\DomainBundle\Entity\Test\\',
            'Exam\DomainBundle\Entity\User\\'
        ];
    }

    /**
     * To Create new repository
     * Naming 'Repository' after domain name is required
     * @return string
     */
    protected function getClass(){
        $target = str_replace('Repository', '', (new \ReflectionClass($this))->getShortName());

        foreach($this->getDomainNamespaces() as $namespace) {
            $className = $namespace.$target;

            if(class_exists($className)) {
                $domain = new \ReflectionClass($className);
            }
        }

        return $domain->getName();
    }
}