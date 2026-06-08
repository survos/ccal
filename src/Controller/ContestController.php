<?php

// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Contest;
use App\Form\ContestType;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contest/{contestId}')]
class ContestController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_contest_show')]
    public function show(Contest $contest): Response
    {
        return $this->render('contest/show.html.twig', [
            'contest' => $contest,
        ]);
    }

    #[Route('/edit', name: 'app_contest_edit')]
    public function edit(Request $request, Contest $contest): Response
    {
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contest_index');
        }

        return $this->render('contest/edit.html.twig', [
            'contest' => $contest,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'app_contest_delete', methods: ['DELETE'])]
    public function delete(Request $request, Contest $contest): Response
    {
        // hard-coded to getId, should be get parameter of uniqueIdentifiers()
        if ($this->isCsrfTokenValid('delete'.$contest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->entityManager;
            $entityManager->remove($contest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contest_index');
    }
}
