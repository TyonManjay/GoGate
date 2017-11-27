<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quantum
 * Date: 2/18/14
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Exam\WebBundle\Service;

use Exam\DomainBundle\Entity\User\User;
use Exam\DomainBundle\Repository\ParticipantRepository;
use Exam\DomainBundle\Repository\UserRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpFoundation\Session\Session;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * Class LoginService
 * @package Exam\WebBundle\Service
 * @Service("loginService")
 */
class LoginService {
    private $session,
            $userRepo,
            $repo;

    /**
     * @InjectParams({
     *      "session" = @Inject("session"),
     *      "userRepo" = @Inject("userRepo"),
     *      "repo" = @Inject("participantRepo")
     * })
     */
    public function __construct(Session $session,
                                UserRepository $userRepo,
                                ParticipantRepository $repo) {
        $this->session = $session;
        $this->userRepo = $userRepo;
        $this->repo = $repo;
    }

    private function findUser($registrationNumber, $password) {
        // var_dump($password);die;
        return $this->userRepo->findOneBy(array(
            "username" => $registrationNumber,
            "password" => $password
        ));
      // var_dump($manjay);die;
    }

    public function participantJoin($registrationNumber, $password) {
        //var_dump($registrationNumber);die;
        // var_dump($this->findUser($registrationNumber, $password));die;

        if((bool)($user = $this->findUser($registrationNumber, $password))) {

            $participant = $this->getParticipant($user);
            $this->session->set('participant', $participant->getId());
            // var_dump($participant->getId());die;
        }
    }

    public function isLogin() {
        return $this->session->has('participant');
    }

    public function logout() {
        if($this->isLogin()) {
            $this->session->clear();
        }
    }

    public function getParticipant(User $user) {
        return $this->repo->findOneBy(array('user' => $user));
    }

    public function getCurrentParticipant() {
        return $this->repo->find($this->session->get('participant'));
    }
}