<?php

namespace Exam\AdminBundle\Controller;

use Exam\DomainBundle\Entity\Test\Enrollment;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class FrontController extends BaseController {
    private $enrollmentRepo;

    /**
     * @InjectParams
     */
    public function __construct(EnrollmentRepository $enrollmentRepo) {
        $this->enrollmentRepo = $enrollmentRepo;
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function index() {
        $enrollments = $this->enrollmentRepo->all();

        return $this->render('front/index.html.twig', [
            'onGoingEnrollments' => array_filter($enrollments, function(Enrollment $enrollment) {
                return $enrollment->isStarted() and !$enrollment->isFinished();
            })
        ]);
    }
}