<?php

namespace App\Controller;

use App\Entity\Org;
use App\Form\OrgType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrgRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/org/{orgId}')]
class OrgController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/', name: 'org_show', options: ['expose' => true])]
    public function show(Org $org): Response
    {
        return $this->render('org/show.html.twig', [
            'org' => $org,
        ]);
    }

    #[Route('/calendars', name: 'org_calendars', options: ['expose' => true])]
    public function calendars(Org $org): Response
    {
        return $this->render('org/calendars.html.twig', [
            'org' => $org,
        ]);
    }

    #[Route('/edit', name: 'org_edit', options: ['expose' => true])]
    public function edit(Request $request, Org $org): Response
    {
        $form = $this->createForm(OrgType::class, $org);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('org_index');
        }

        return $this->render('org/edit.html.twig', [
            'org' => $org,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'org_delete', methods: ['DELETE'])]
    public function delete(Request $request, Org $org): Response
    {
        // hard-coded to getId, should be get parameter of uniqueIdentifiers()
        if ($this->isCsrfTokenValid('delete' . $org->getId(), $request->request->get('_token'))) {
            $entityManager = $this->entityManager;
            $entityManager->remove($org);
            $entityManager->flush();
        }

        return $this->redirectToRoute('org_index');
    }
}
