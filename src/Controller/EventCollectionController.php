<?php


// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/event')]
class EventCollectionController extends AbstractController
{

const PLACE_NEW = 'new';

public function __construct(private EntityManagerInterface $entityManager) {
   $this->marking = self::PLACE_NEW;

}

#[Route(path: '/list/{marking}', name: 'event_browse', methods: ['GET'])]
public function browse(string $marking=Event::PLACE_NEW): Response
{
$class = Event::class;
// WorkflowInterface $projectStateMachine
$markingData = []; // $this->workflowHelperService->getMarkingData($projectStateMachine, $class);

return $this->render('event/browse.html.twig', [
'class' => $class,
'marking' => $marking,
'filter' => [],
//            'owner' => $owner,
]);
}

#[Route('/index', name: 'event_index')]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findBy([], [], 30),
        ]);
    }

#[Route('event/new', name: 'event_new')]
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
}
