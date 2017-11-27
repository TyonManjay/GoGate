<?php
/**
 * Created by JetBrains PhpStorm.
 * User: quantum
 * Date: 2/18/14
 * Time: 8:54 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Exam\WebBundle\Controller;

use Exam\WebBundle\Service\LoginService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * Class LoginController
 * @package Exam\WebBundle\Controller
 */
class LoginController extends Controller {
    private $service;
    /**
     * @InjectParams({
     *      "service" = @Inject("LoginService")
     * })
     */
    public function __construct(LoginService $service) {
        $this->service = $service;
    }

    /**
     * @Route("/")
     * @Route("/login")
     * @Method({"GET"})
     */
    public function login() {
        if($this->service->isLogin()) {
            return " hi";die;
        }

        return $this->render('ExamWebBundle:Login:login.html.twig');
    }

    /**
     * @Route("/")
     * @Route("/login")
     * @Method({"POST"})
     */
    public function loginCheck(Request $request) {
        $username = $request->get('participant-id');

        $password = $request->get('participant-password');
        // var_dump($password); die;

         $this->service->participantJoin($username, $password);
        // var_dump($this->service->participantJoin($username, $password));die;

        return $this->redirect('/exam');
    }

    /**
     * @Route("/logout")
     * @Method({"POST"})
     */
    public function logout() {
        $this->service->logout();

        return $this->redirect('/');
    }
}