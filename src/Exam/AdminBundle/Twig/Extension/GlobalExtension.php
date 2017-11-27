<?php

namespace Exam\AdminBundle\Twig\Extension;

use Exam\DomainBundle\Entity\Test\Enrollment;
use Exam\DomainBundle\Repository\AdminRepository;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use Exam\DomainBundle\Repository\PackageRepository;
use Exam\DomainBundle\Repository\ParticipantRepository;
use Exam\WebBundle\Service\ScoringService;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpFoundation\Session\Session;

class GlobalExtension extends \Twig_Extension {
    private $participantRepo;
    private $packageRepo;
    private $enrollmentRepo;
    private $adminRepo;
    private $session;

    public function __construct(ParticipantRepository $participantRepo, PackageRepository $packageRepo, EnrollmentRepository $enrollmentRepo, AdminRepository $adminRepo, Session $session) {
        $this->participantRepo = $participantRepo;
        $this->packageRepo = $packageRepo;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->adminRepo = $adminRepo;
        $this->session = $session;
    }

    public function getFunctions() {
        return array_merge(parent::getFunctions(), [
            new \Twig_SimpleFunction('score', function(Enrollment $enrollment) {
                return ScoringService::score($enrollment);
            })
        ]);
    }

    public function getGlobals() {
        $enrollments = $this->enrollmentRepo->all();

        if(!$this->session->has('admin')) {
            return parent::getGlobals();
        }

        return array_merge(parent::getGlobals(), [
            'admin' =>  $this->adminRepo->find($this->session->get('admin')),
            'participantsCount' => $this->participantRepo->count(),
            'packagesCount' => $this->packageRepo->count(),
            'enrollmentsCount' => [
                'finished' => count(array_filter($enrollments, function(Enrollment $enrollment) {
                    return $enrollment->isFinished();
                })),
                'onProgress' => count(array_filter($enrollments, function(Enrollment $enrollment) {
                    return ($enrollment->isStarted() and !$enrollment->isFinished());
                })),
                'notStarted' => count(array_filter($enrollments, function(Enrollment $enrollment) {
                    return !$enrollment->isStarted();
                }))
            ]
        ]);
    }

    public function getName() {
        return 'global_extension';
    }
}