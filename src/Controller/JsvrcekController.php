<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Jsvrcek\ICS\CalendarExport;
use Jsvrcek\ICS\CalendarStream;
use Jsvrcek\ICS\Model\Calendar;
use Jsvrcek\ICS\Model\CalendarAlarm;
use Jsvrcek\ICS\Model\CalendarEvent;
use Jsvrcek\ICS\Model\Relationship\Attendee;
use Jsvrcek\ICS\Model\Relationship\Organizer;
use Jsvrcek\ICS\Utility\Formatter;

#[Route('/jsvrcek')]
class JsvrcekController extends AbstractController
{
    #[Route('/', name: 'jsvrcek_index')]
    public function index(): Response
    {
        return $this->render('jsvrcek/index.html.twig', [
            'controller_name' => 'JsvrcekController',
        ]);
    }

    #[Route('/ics', name: 'jsvrcek_ics')]
    public function ics_from_bundle(): Response
    {

        //setup an event
        $eventOne = new CalendarEvent();
        $eventOne->setStart(new \DateTime())
            ->setSummary('Family reunion')
            ->setUid('event-uid');

//add an Attendee
        $attendee = new Attendee($this->formatter);
        $attendee->setValue('moe@example.com')
            ->setName('Moe Smith');
        $eventOne->addAttendee($attendee);

//set the Organizer
        $organizer = new Organizer($this->formatter);
        $organizer->setValue('heidi@example.com')
            ->setName('Heidi Merkell')
            ->setLanguage('de');
        $eventOne->setOrganizer($organizer);

//new event
        $eventTwo = new CalendarEvent();
        $eventTwo->setStart(new \DateTime())
            ->setSummary('Dentist Appointment')
            ->setUid('event-uid');

//setup calendar
        $calendar = new Calendar();
        $calendar->setProdId('-//My Company//Cool Calendar App//EN')
            ->addEvent($eventOne)
            ->addEvent($eventTwo);

//setup exporter
        $this->calendarExport->addCalendar($calendar);
        $response = new Response($this->calendarExport->getStream());
        $response->headers->set('Content-Type', 'text/calendar');

        return $response;

    }


}
