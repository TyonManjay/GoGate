<?php

namespace Exam\WebBundle\Service;

use Exam\DomainBundle\Entity\Test\Enrollment;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("ScoringService")
 */
class ScoringService {
    public static function score(Enrollment $enrollment) {
        $package = $enrollment->getPackage();
        $correctAnswers = 0;

        foreach($package->getQuestions() as $question) {
            $attempts = $enrollment->getAttemptsFor($question->getId());

            if($attempts->count() !== 0) {
                if($attempts->last()->getAnswer() === $question->getAnswer()) {
                    $correctAnswers++;
                }
            }
        }

        return $package->getTotalQuestions() === 0
            ? 0
            : ceil(($correctAnswers / $package->getTotalQuestions()) * 100);
    }
}