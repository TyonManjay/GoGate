<?php

namespace Exam\DomainBundle\Naming;

use Doctrine\ORM\Mapping\DefaultNamingStrategy;

class MySqlNamingStrategy extends DefaultNamingStrategy {
    private $prefix;

    public function __construct($prefix) {
        $this->prefix = $prefix;
    }

    function classToTableName($className) {
        $className = parent::classToTableName($className);
        $className = self::clearString($className);
        $className = self::pluralize($className);

        return $this->prefix.lcfirst($className);
    }

    function joinTableName($sourceEntity, $targetEntity, $propertyName = null){
        return $this->prefix.lcfirst(parent::classToTableName($sourceEntity)).self::pluralize(parent::classToTableName($targetEntity));
    }

    function joinKeyColumnName($entityName, $referencedColumnName = null) {
        return lcfirst(parent::classToTableName($entityName))."_".parent::referenceColumnName();
    }

    private function clearString($string) {
        return preg_replace('[^0-9a-zA-Z]', '', $string);
    }

    private function pluralize($string) {
        $vowel = ['a', 'i', 'u', 'e', 'o'];
        $consonants = ['h', 's'];

        $chars = str_split($string);
        $lastLetter = array_pop($chars);

        if (in_array($lastLetter, $vowel))
            if ($lastLetter === 'o') return $string.'es';
            else return $string.'s';
        else
            if (in_array($lastLetter, $consonants)) return $string.'es';
            else if ($lastLetter === 'y')  return substr($string, 0, -1).'ies';

        return $string.'s';
    }
}