<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\FeedRepository;
use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\Utility\Formatter;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    public function __construct() {

    }

    #[Route('/ics.ics', name: 'app_calendar_ics', methods: ['POST','GET'])]
    public function ics(Request $request, EventRepository $eventRepository): Response
    {

        $calendar = Calendar::create('Test Calendar');

        $event = Event::create()
            ->name('Laracon Online')
            ->description('Experience Laracon all around the world')
            ->uniqueIdentifier('A unique identifier can be set here')
            ->createdAt(new \DateTimeImmutable())
            ->startsAt(new \DateTimeImmutable())
            ->endsAt(new \DateTimeImmutable("+ 1hour"));

        $ics = $calendar
            ->event($event)
            ->get();
//        dd($ics);
        return new Response($ics, 200, ['Content-Type'=> 'text/calendar']);
    }


    #[Route('/', name: 'app_homepage')]
    public function index(UrlGeneratorInterface $urlGenerator, FeedRepository $feedRepository): Response
    {
        // Build the legend map (id => {label, color}) from the DB feeds; the id is the
        // feed slug, which matches each event's sourceId so the toggles line up.
        $calendars = [];
        foreach ($feedRepository->findAll() as $feed) {
            $calendars[$feed->getSlug()] = ['label' => $feed->title ?? $feed->getSlug(), 'color' => $feed->color];
        }

        return $this->render('app/index.html.twig', [
            'eventsUrl' => $urlGenerator->generate('survos_ux_calendar_feed'),
            'calendars' => $calendars,
        ]);
    }


    #[Route('/stimulus', name: 'app_stimulus')]
    public function stimulus(): Response
    {
        return $this->render('app/stimulus.html.twig', [
        ]);
    }

    #[Route('/menu', name: 'app_menu')]
    public function menu(): Response
    {
        return $this->render('app/mmenu_light.html.twig', [
//        return $this->render('app/bootstrap_collapsible_menu.html.twig', [
        ]);
    }
}
