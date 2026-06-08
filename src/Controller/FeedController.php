<?php

// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/feed/{feedId}')]
class FeedController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'feed_show')]
    public function show(Feed $feed): Response
    {
        ray( $feed);
        return $this->render('feed/show.html.twig', [
            'feed' => $feed,
        ]);
    }

    #[Route('/edit', name: 'feed_edit')]
    public function edit(Request $request, Feed $feed): Response
    {
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('feed_index');
        }

        return $this->render('feed/edit.html.twig', [
            'feed' => $feed,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete', name: 'feed_delete', methods: ['DELETE'])]
    public function delete(Request $request, Feed $feed): Response
    {
        // hard-coded to getId, should be get parameter of uniqueIdentifiers()
        if ($this->isCsrfTokenValid('delete'.$feed->getId(), $request->request->get('_token'))) {
            $entityManager = $this->entityManager;
            $entityManager->remove($feed);
            $entityManager->flush();
        }

        return $this->redirectToRoute('feed_index');
    }
}
