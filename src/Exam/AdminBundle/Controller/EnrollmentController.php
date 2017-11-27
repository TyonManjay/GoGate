<?php

namespace Exam\AdminBundle\Controller;

use Exam\AopBundle\Authorize;
use Exam\AopBundle\Transactional;
use Exam\DomainBundle\Entity\Test\Enrollment;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use Exam\DomainBundle\Repository\PackageRepository;
use Exam\DomainBundle\Repository\ParticipantRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/enrollments")
 */
class EnrollmentController extends BaseController {
    private $enrollmentRepo;
    private $participantRepo;
    private $packageRepo;

    /**
     * @InjectParams
     */
    public function __construct(EnrollmentRepository $enrollmentRepo, ParticipantRepository $participantRepo, PackageRepository $packageRepo) {
        $this->enrollmentRepo = $enrollmentRepo;
        $this->participantRepo = $participantRepo;
        $this->packageRepo = $packageRepo;
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function showAll() {
        return $this->render('/enrollments/all.html.twig', [
            'enrollments' => $this->enrollmentRepo->findAll([], [
                'enrolledOn' => 'desc'
            ])
        ]);
    }

    /**
     * @Route("/add")
     * @Method({"POST"})
     * @Authorize
     * @Transactional
     */
    public function addNew(Request $request) {
        $package = $this->packageRepo->find($request->get('package'));
        $participant = $this->participantRepo->find($request->get('participant'));

        $this->enrollmentRepo->persist(
            new Enrollment($participant, $package)
        );

        return $this->redirect($request->server->get('HTTP_REFERER'), 302, [
            'success' => 'New enrollment added successfully'
        ]);
    }

    /**
     * @Route("/remove")
     * @Method({"POST"})
     * @Transactional
     */
    public function remove(Request $request) {
        $enrollment = $this->enrollmentRepo->find($request->get('id'));

        if($enrollment->isStarted()) {
            return $this->redirect($request->server->get('HTTP_REFERER'), 302, [
                'error' => 'Enrollment is already started and cannot be removed.'
            ]);
        }

        $this->enrollmentRepo->remove($enrollment);

        return $this->redirect('/admin/enrollments/all', 302, [
            'success' => 'Enrollment removed'
        ]);
    }

    /**
     * @Route("/restart")
     * @Method({"POST"})
     * @Transactional
     */
    public function restart(Request $request) {
        $enrollment = $this->enrollmentRepo->find($request->get('id'));

        $this->enrollmentRepo
             ->persist($enrollment->restart());

        return $this->redirect('/admin/enrollments', 302, [
            'success' => 'Enrollment restarted'
        ]);
    }

    /**
     * @Route("/detail/{id}")
     * @Method({"GET"})
     */
    public function showDetail($id) {
        return $this->render('enrollments/detail.html.twig', [
            'enrollment' => $this->enrollmentRepo->find($id)
        ]);
    }
}