<?php

namespace Exam\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends Controller {
    /**
     * {@inheritDoc}
     */
    public function render($view, array $parameters = array(), Response $response = null) {
        return parent::render($this->getView($view), $parameters, $response);
    }

    /**
     * {@inheritDoc}
     */
    public function renderView($view, array $parameters = array()) {
        return parent::render($this->getView($view), $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function redirect($url, $status = 302, $messages = array()) {
        $flashBag = $this->get('session')->getFlashBag();

        foreach($messages as $type => $message) {
            $flashBag->add($type, $message);
        }

        return parent::redirect($url, $status);
    }

    private function getView($view) {
        $realView = str_replace('\\', '/', $view);
        $realView = explode('/', $realView);
        $templateName = array_pop($realView);

        array_walk($realView, function(&$piece){
            $piece = strpos($piece, '.') ? $piece : ucfirst($piece);
        });

        $realView = implode('/', $realView).BUNDLE_SEPARATOR.$templateName;
        $realView = strpos($realView, $this->getBundleName())
            ? $realView
            : $this->getBundleName().BUNDLE_SEPARATOR.$realView;

        return $realView;
    }

    function getBundleName() {
        return 'ExamAdminBundle';
    }
}