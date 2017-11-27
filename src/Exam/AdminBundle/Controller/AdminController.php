<?php

namespace Exam\AdminBundle\Controller;

use Exam\DomainBundle\Repository\AdminRepository;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends BaseController {
    private $adminRepo;

    /**
     * @InjectParams
     */
    public function __construct(AdminRepository $adminRepo) {
        $this->adminRepo = $adminRepo;
    }

    /**
     * @Route("/changePassword")
     * @Method({"GET"})
     */
    public function showChangePassword() {
        return $this->render('admin/changePassword.html.twig');
    }

    /**
     * @Route("/changePassword")
     * @Method({"POST"})
     */
    public function changePassword(Request $request) {
        $admin = $this->adminRepo->find($request->get('id'));

        if($request->get('oldPassword') !== $request->get('oldPasswordConfirm')) {
            return $this->redirect($request->server->get('HTTP_REFERER'), 302, [
                'error' => 'Old password and old password confirmation are not match'
            ]);
        }
        elseif($admin->getUser()->getPassword() !== md5($request->get('oldPassword'))) {
            return $this->redirect($request->server->get('HTTP_REFERER'), 302, [
                'error' => 'Wrong old password'
            ]);
        }

        $this->adminRepo->persist(
            $admin->getUser()->setPassword($request->get('newPassword'))
        );

        return $this->redirect('/admin', 302, [
            'success' => 'Password changed'
        ]);
    }
}