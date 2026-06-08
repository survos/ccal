<?php

// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Contest;
use App\Form\ContestType;
use App\Repository\ContestRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @Route("/contest/entity")
 */
class ContestEntityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_contest_index')]
    public function index(ContestRepository $contestRepository): Response
    {
        return $this->render('contest/index.html.twig', [
            'contests' => $contestRepository->findBy([], [], 30),
        ]);
    }

    #[Route('app_contest/new', name: 'app_contest_new')]
    public function new(Request $request): Response
    {
        $contest = new Contest();
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $entityManager->persist($contest);
            $entityManager->flush();

            return $this->redirectToRoute('app_contest_index');
        }

        return $this->render('contest/new.html.twig', [
            'contest' => $contest,
            'form' => $form->createView(),
        ]);
    }
}
