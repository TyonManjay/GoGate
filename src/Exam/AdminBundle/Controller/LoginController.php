<?php

namespace Exam\AdminBundle\Controller;

use Exam\DomainBundle\Repository\AdminRepository;
use Exam\DomainBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/admin")
 */
class LoginController extends BaseController {
    private $adminRepo;
    private $userRepo;
    private $session;

    /**
     * @InjectParams
     */
    public function __construct(AdminRepository $adminRepo, UserRepository $userRepo, Session $session) {
        $this->adminRepo = $adminRepo;
        $this->userRepo = $userRepo;
        $this->session = $session;
    }

    /**
     * @Route("/login")
     * @Method({"GET"})
     */
    public function showLogin() {

        if($this->session->has('admin')) {

            return $this->redirect('/admin');
            

        }

        // return $this->render('front/login.html.twig');
        return $this->render('ExamAdminBundle:Font:login.html.twig');

    }

    /**
     * @Route("/login")
     * @Method({"POST"})
     */
    public function doLogin(Request $request) {

        $user = $this->userRepo->findOneBy(array(
            "username" => $request->get('username'),
            "password" => $request->get('password')
        ));
        
        
        $admin = $this->adminRepo->findOneBy(array('user' => $user));

        if(!$admin) {
            return $this->redirect('/admin/login', 302, [
                'error' => 'Wrong username or password'
            ]);
        }

        $this->session->set('admin', $admin->getId());

        return $this->redirect('/admin');
    }

    /**
     * @Route("/logout")
     * @Method({"GET"})
     */
    public function doLogout() {
        $this->session->remove('admin');

        return $this->redirect('/admin/login');
    }
}