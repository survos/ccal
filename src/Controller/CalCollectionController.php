<?php


// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Cal;
use App\Form\CalType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cal')]
class CalCollectionController extends AbstractController
{


const PLACE_NEW = 'new';

public function __construct(private EntityManagerInterface $entityManager) {
   $this->marking = self::PLACE_NEW;

}

#[Route(path: '/list/{marking}', name: 'cal_browse', methods: ['GET'])]
public function browse(string $marking=Cal::PLACE_NEW): Response
{
$class = Cal::class;
// WorkflowInterface $projectStateMachine
$markingData = []; // $this->workflowHelperService->getMarkingData($projectStateMachine, $class);

return $this->render('cal/browse.html.twig', [
'class' => $class,
'marking' => $marking,
'filter' => [],
//            'owner' => $owner,
]);
}

#[Route('/index', name: 'cal_index')]
    public function index(CalRepository $calRepository): Response
    {
        return $this->render('cal/index.html.twig', [
            'cals' => $calRepository->findBy([], [], 30),
        ]);
    }

#[Route('cal/new', name: 'cal_new')]
    public function new(Request $request): Response
    {
        $cal = new Cal();
        $form = $this->createForm(CalType::class, $cal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $entityManager->persist($cal);
            $entityManager->flush();

            return $this->redirectToRoute('cal_index');
        }

        return $this->render('cal/new.html.twig', [
            'cal' => $cal,
            'form' => $form->createView(),
        ]);
    }
}
