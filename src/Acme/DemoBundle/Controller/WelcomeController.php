<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/*
 * The action's view can be rendered using render() method
 * @Template()
 * @Route()
 *
 */
class WelcomeController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('AcmeDemoBundle:Welcome:index.html.twig');
    }
}
