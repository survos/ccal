<?php


// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Org;
use App\Form\OrgType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrgRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/org')]
class OrgCollectionController extends AbstractController
{



    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route(path: '/list/{marking}', name: 'org_browse', methods: ['GET'])]
    public function browse(string $marking = Org::PLACE_NEW): Response
    {
        $class = Org::class;
// WorkflowInterface $projectStateMachine
        $markingData = []; // $this->workflowHelperService->getMarkingData($projectStateMachine, $class);

        return $this->render('org/browse.html.twig', [
            'class' => $class,
            'marking' => $marking,
            'filter' => [],
//            'owner' => $owner,
        ]);
    }

    #[Route('/index', name: 'org_index')]
    public function index(OrgRepository $orgRepository): Response
    {
        return $this->render('org/index.html.twig', [
            'orgs' => $orgRepository->findBy([], [], 30),
        ]);
    }

    #[Route('org/new', name: 'org_new')]
    public function new(Request $request): Response
    {
        $org = new Org();
        $form = $this->createForm(OrgType::class, $org);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $entityManager->persist($org);
            $entityManager->flush();

            return $this->redirectToRoute('org_index');
        }

        return $this->render('org/new.html.twig', [
            'org' => $org,
            'form' => $form->createView(),
        ]);
    }
}
