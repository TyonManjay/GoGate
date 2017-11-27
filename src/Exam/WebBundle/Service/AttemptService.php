<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quantum
 * Date: 2/19/14
 * Time: 10:00 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Exam\WebBundle\Service;


use Exam\DomainBundle\Entity\Test\Attempt;
use Exam\DomainBundle\Repository\AttemptRepository;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use Exam\DomainBundle\Repository\QuestionRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class AttemptService
 * @package Exam\WebBundle\Service
 * @Service("attemptService")
 */
class AttemptService {
    private $attemptRepo,
            $questionRepo,
            $enrollService,
            $crudService;
    /**
     * @InjectParams({
     *      "attemptRepo" = @Inject("attemptRepo"),
     *      "questionRepo" = @Inject("questionRepo"),
     *      "enrollmentService" = @Inject("enrollmentService"),
     *      "crudService" = @Inject("crudService")
     * })
     */
    public function __construct(AttemptRepository $attemptRepo,
                                QuestionRepository $questionRepo,
                                EnrollmentService $enrollmentService,
                                CRUDService $crudService) {
        $this->attemptRepo = $attemptRepo;
        $this->questionRepo = $questionRepo;
        $this->enrollService = $enrollmentService;
        $this->crudService = $crudService;
    }

    public function addAttempt($questionId, $answerId) {

        $package = $this->enrollService->getCurrentPackage();

        $questions = $package->getQuestions()->filter(
            function($question) use ($questionId) {
                return $question->getId() == $questionId;
            }
        );

        $answers = $questions->first()->getOptions()->filter(
            function($option) use ($answerId) {
                return $option->getId() == $answerId;
            }
        );

        $attempt = new Attempt(
            $this->enrollService->getEnrollment(),
            $questions->first(),
            $answers->first()
        );

        $enrollment = $this->enrollService->getEnrollment()->addAttempts($attempt);

        $this->crudService->update($enrollment);
    }

}