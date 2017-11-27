<?php
namespace Exam\WebBundle\DependencyInjection;

use Exam\WebBundle\Resources\globals\Config;
use Exam\WebBundle\Service\EnrollmentService;
use Exam\WebBundle\Service\LoginService;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * Class TwigExtension
 * @package Exam\WebBundle\DependencyInjection
 */
class TwigExtension extends \Twig_Extension {
    private $loginService,
            $enrollmentService;

    public function __construct(LoginService $loginService,
                                EnrollmentService $enrollmentService) {
        $this->loginService = $loginService;
        $this->enrollmentService = $enrollmentService;
    }

    public function getGlobals() {
        return array(
            "timer" => ($this->loginService->isLogin() and $this->enrollmentService->hasEnrollment()) ? $this->enrollmentService->getEnrollment()->getTimeleft(false) : 'null',
            "participant" => $this->loginService->isLogin() ? $this->loginService->getCurrentParticipant() : null,
            "divisor" => Config::DIVISOR
        );
    }

    public function getFilters() {
        return array(
            'ceil' => new \Twig_Filter_Method($this, 'ceil')
        );
    }

    public function ceil($number) {
        return ceil($number);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return "twig_extension";
    }
}