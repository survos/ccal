<?php

// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use App\Repository\FeedRepository;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/feed')]
class FeedCollectionController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'feed_index')]
    public function index(FeedRepository $feedRepository): Response
    {
        return $this->render('feed/index.html.twig', [
            'feeds' => $feedRepository->findBy([], [], 30),
        ]);
    }

    #[Route('feed/new', name: 'feed_new')]
    public function new(Request $request): Response
    {
        $feed = new Feed();
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $entityManager->persist($feed);
            $entityManager->flush();

            return $this->redirectToRoute('feed_show', $feed->getRP());
        }

        return $this->render('feed/new.html.twig', [
            'feed' => $feed,
            'form' => $form->createView(),
        ]);
    }
}
