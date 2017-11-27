<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quantum
 * Date: 2/18/14
 * Time: 12:00 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Exam\WebBundle\Service;

use Exam\DomainBundle\Entity\Test\Enrollment;
use Exam\DomainBundle\Entity\Test\Package;
use Exam\DomainBundle\Entity\User\Participant;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use Exam\DomainBundle\Repository\PackageRepository;
use Exam\DomainBundle\Repository\ParticipantRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * Class EnrollmentService
 * @package Exam\WebBundle\Service
 * @Service("enrollmentService")
 */
class EnrollmentService {

    private $enrollmentId,
            $session,
            $enrollmentRepo,
            $packageRepo,
            $participantRepo,
            $loginService,
            $crudService;

    /**
     * @InjectParams({
     *      "session" = @Inject("session"),
     *      "enrollmentRepo" = @Inject("enrollmentRepo"),
     *      "packageRepo" = @Inject("packageRepo"),
     *      "participantRepo" = @Inject("participantRepo"),
     *      "loginService" = @Inject("loginService"),
     *      "crudService" = @Inject("crudService")
     * })
     */
    public function __construct(Session $session,
                                EnrollmentRepository $enrollmentRepo,
                                PackageRepository $packageRepo,
                                ParticipantRepository $participantRepo,
                                LoginService $loginService,
                                CRUDService $crudService) {
        $this->session = $session;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->packageRepo = $packageRepo;
        $this->participantRepo = $packageRepo;
        $this->loginService = $loginService;
        $this->crudService = $crudService;
        $this->enrollmentId = $this->session->get('enrollment');
    }

    public function hasEnrollment() {
        return $this->session->has('enrollment');
    }

    private function setEnrollment($enrollmentId) {
        if(!$this->authorizeEnrollmentSession()) {
            $enrollment = $this->enrollmentRepo->findOneBy(array(
                "id" => $enrollmentId,
                "participant" => $this->loginService->getCurrentParticipant(),
            ));

            if(!$enrollment) { throw new \Exception("not found!"); }

            $this->session->set('enrollment', $enrollment->getId());

            $this->enrollmentId = $enrollment->getId();
        }

        return $this;
    }

    public function authorizeEnrollmentSession() {
        return $this->session->has('enrollment');
    }

    public function removeEnrollment() {
        if($this->hasEnrollment()) {
            $this->session->remove('enrollment');
        }
    }

    public function getCurrentPackage() {
        $enrollment = $this->enrollmentRepo->find($this->enrollmentId);
        return $enrollment->getPackage();
    }

    public function getEnrollment() {
        return $this->enrollmentRepo->find($this->enrollmentId);
    }

    public function getEnrollments() {
        return $this->enrollmentRepo->findBy(array(
            'participant' => $this->loginService->getCurrentParticipant()
        ));
    }

    public function getAvailableEnrollments() {
        return $this->enrollmentRepo->createQueryBuilder('enroll')
            ->where('enroll.participant = :p and (enroll.startedOn IS NULL and enroll.finishedOn IS NULL or enroll.startedOn IS NOT NULL and enroll.finishedOn IS NULL)')
            ->setParameter('p', $this->loginService->getCurrentParticipant())
            ->getQuery()
            ->execute();

    }

    public function startEnrollment($enrollmentId) {
        if($this->authorizeEnrollmentSession()) return false;

        $this->setEnrollment($enrollmentId);

        if(!$this->getEnrollment()->isStarted()) {

            $this->crudService->update(
                $this->getEnrollment()->start()
            );
        }
    }

    public function finishEnrollment() {
        if($this->getEnrollment()->isStarted()) {
            $this->crudService->update(
                $this->getEnrollment()->finish()
            );

            $this->removeEnrollment();
        }
    }

    public function restartEnrollment() {
        if($this->getEnrollment()->isStarted()) {
            $this->crudService->update(
                $this->getEnrollment()->restart()
            );
        }
    }

    public function getAvailablePackagesFor(Participant $participant) {
        $packages = $this->packageRepo->all();
        $activeEnrollments = array_filter(
            $this->enrollmentRepo->findForParticipants($participant),
            function(Enrollment $enrollment) {
                return !$enrollment->isFinished();
            }
        );

        return array_filter($packages, function(Package $package) use($activeEnrollments) {
            return count(array_filter($activeEnrollments, function(Enrollment $enrollment) use($package) {
                return $enrollment->getPackage() === $package;
            })) === 0;
        });
    }

    public function createEnrollment($participantId, $packageId) {
        $participant = $this->participantRepo->find($participantId);
        $package = $this->packageRepo->find($packageId);

        if(!in_array($this->getAvailablePackagesFor($participant), $package)) {
            throw new Exception("Package is not available for participant");
        }

        return new Enrollment($participant, $package);
    }

    public function validateExpiredEnrollments() {
        if($this->loginService->isLogin()) {
            foreach($this->getAvailableEnrollments() as $enrollment) {
                if($enrollment->isStarted() and !$enrollment->isFinished()) {
                    if($enrollment->getTimeleft(false) <= 0) {
                        $enrollment->finish();

                        $this->crudService->update($enrollment);
                    }
                }
            }
            $this->crudService->save();
        }
    }
}