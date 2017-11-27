<?php

namespace Exam\AdminBundle\Controller;

use Exam\AopBundle\Transactional;
use Exam\DomainBundle\Entity\Test\Enrollment;
use Exam\DomainBundle\Entity\Test\Option;
use Exam\DomainBundle\Entity\Test\Package;
use Exam\DomainBundle\Entity\Test\Question;
use Exam\DomainBundle\Repository\EnrollmentRepository;
use Exam\DomainBundle\Repository\PackageRepository;
use Exam\DomainBundle\Repository\ParticipantRepository;
use Exam\DomainBundle\Repository\QuestionRepository;
use Exam\WebBundle\Service\CRUDService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/packages")
 */
class PackageController extends BaseController {
    private $packageRepo;
    private $enrollmentRepo;
    private $participantRepo;
    private $crudService;

    /**
     * @InjectParams({
     *      "crudService" = @Inject("CRUDService")
     * })
     */
    public function __construct(PackageRepository $packageRepo,
                                EnrollmentRepository $enrollmentRepo,
                                ParticipantRepository $participantRepo,
                                CRUDService $crudService) {
        $this->packageRepo = $packageRepo;
        $this->enrollmentRepo = $enrollmentRepo;
        $this->participantRepo = $participantRepo;
        $this->crudService = $crudService;
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function showAll() {
        return $this->render('packages/all.html.twig', [
            'packages' => $this->packageRepo->all()
        ]);
    }

    /**
     * @Route("/create")
     * @Method({"GET"})
     */
    public function showCreate() {
        return $this->render('packages/create.html.twig');
    }

    /**
     * @Route("/create")
     * @Method({"POST"})
     * @Transactional
     */
    public function create(Request $request) {
        $this->packageRepo->persist(
            new Package($request->get('name'))
        );

        return $this->redirect('/admin/packages', 302, [
            'success' => 'Package created successfully'
        ]);
    }

    /**
     * @Route("/{id}/questions")
     * @Method({"GET"})
     */
    public function showAllQuestions($id) {
        return $this->render('packages/questions/all.html.twig', [
            'package' => $this->packageRepo->find($id)
        ]);
    }

    /**
     * @Route("/{id}/questions/add")
     * @Method({"GET"})
     */
    public function showAddQuestions($id) {
        return $this->render('packages/questions/add.html.twig', [
            'package' => $this->packageRepo->find($id)
        ]);
    }

    /**
     * @Route("/questions/add")
     * @Method({"POST"})
     * @Transactional
     */
    public function addQuestion(Request $request) {
        $package = $this->packageRepo->find($request->get('id'));
        $question = new Question($request->get('question-description'));

        foreach($request->get('options') as $idx => $option) {
            $option = new Option($option);

            $question->addOption($option);

            if($request->get('answer') == $idx) {
                $answer = $option;
            }
        }

        $package->addQuestion($question);

        $this->crudService->save($package);

        if(isset($answer)) {
            $question->setAnswer($answer->getId());
        }

        $this->crudService->save($question);

        return $this->redirect($request->server->get('HTTP_REFERER'), 302, [
            'success' => 'Question added'
        ]);
    }

    /**
     * @Route("/{id}/enroll")
     * @Method({"GET"})
     */
    public function showEnroll($id) {
        $package = $this->packageRepo->find($id);
        $participants = $this->participantRepo->all();
        $enrollments = $this->enrollmentRepo->findForPackage($package);

        return $this->render('/packages/enrollments/all.html.twig', [
            'package' => $package,
            'enrollments' => $enrollments,
            'availableParticipants' => array_filter($participants,
                function($participant) use($enrollments) {
                    return !sizeof(array_filter($enrollments,
                        function(Enrollment $enrollment) use ($participant) {
                            return ($enrollment->getParticipant() === $participant) and !$enrollment->isFinished();
                        })
                    );
            })
        ]);
    }
}