<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quantum
 * Date: 2/14/14
 * Time: 9:31 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Exam\WebBundle\Controller;

use Exam\DomainBundle\Entity\Test\Attempt;
use Exam\WebBundle\Service\AttemptService;
use Exam\WebBundle\Service\EnrollmentService;
use Exam\WebBundle\Service\LoginService;
use Exam\WebBundle\Service\PackageService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Exam\AopBundle\Transactional;
use Exam\DomainBundle\Entity\User\Participant;

/**
 * Class ExamController
 * @package Exam\WebBundle\Controller
 */
class ExamController extends BaseController {

    private $service,
            $enrollmentService,
            $attemptService;

    /**
     * @InjectParams({
     *      "service" = @Inject("loginService"),
     *      "enrollmentService" = @Inject("enrollmentService"),
     *      "attemptService" = @Inject("attemptService")
     * })
     */
    public function __construct(LoginService $service,
                                EnrollmentService $enrollmentService,
                                AttemptService $attemptService) {
        $this->service = $service;
        $this->enrollmentService = $enrollmentService;
        $this->attemptService = $attemptService;
    }


    /**
     * @Route("/exam")
     * @Method({"GET"})
     */
    public function startExam() {
        // var_dump($_SESSION);die;
        // var_dump($this->session()->all());die;
        if(!$this->service->isLogin())  return $this->redirect('/login');


        if(!$this->enrollmentService->hasEnrollment()) {
             // var_dump($this->enrollmentService->getAvailableEnrollments());die;
            return $this->render('ExamWebBundle:Exam:enrollment.html.twig', [
                "all" => $this->enrollmentService->getAvailableEnrollments()
            ]);

        }


        return $this->render('ExamWebBundle:Exam:question.html.twig', [
            "package" => $this->enrollmentService->getCurrentPackage(),
            "enrollment" => $this->enrollmentService->getEnrollment()
        ]);
    }

    /**
     * @Route("/exam/{enrollmentId}")
     * @Method({"GET"})
     * @Transactional
     */
    public function setPackage($enrollmentId) {
        // if(!$this->service->isLogin()) {
        //     return $this->redirect('/login');
        // }

        $this->enrollmentService->startEnrollment($enrollmentId);

        return $this->redirect("/exam");
    }

    /**
     * @Route("/exam/attempt")
     * @Method({"POST"})
     * @Transactional
     */
    public function addAttempt(Request $request) {
        if($request->isXmlHttpRequest()) {
            $param = $request->request;

            $this->attemptService->addAttempt($param->get('questionId'), $param->get('answerId'));

            return new JsonResponse([
                'status'=> 200
            ]);
        }

        return new JsonResponse([
            'status' => 500
        ]);
    }

    /**
     * @Route("/exam/enrollment/finished")
     * @Method({"GET"})
     */
    public function sayThanks() {
        if(!$this->service->isLogin()) return $this->redirect('/login');

        if($this->enrollmentService->hasEnrollment()) return $this->redirect('/exam');

        return $this->render('ExamWebBundle:Exam:thanks.html.twig');
    }

    /**
     * @Route("/exam/enrollment/finished")
     * @Method({"POST"})
     * @Transactional
     */
    public function finishEnrollment(Request $request) {
        if($request->isXmlHttpRequest()) {
            $this->enrollmentService->finishEnrollment();

            return new JsonResponse([
                'status'=> 200,
                'url' => '/exam/enrollment/finished'
            ]);
        }

        if($request->isMethod('POST')) {
            $this->enrollmentService->finishEnrollment();

            return $this->redirect('/exam/enrollment/finished');
        }

        return new JsonResponse([
            'status' => 500
        ]);
    }

}