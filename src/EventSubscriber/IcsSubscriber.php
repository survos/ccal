<?php

namespace App\EventSubscriber;

use App\Entity\Booking;
use App\Entity\Feed;
use App\Repository\BookingRepository;
use App\Service\CalendarService;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use CalendarBundle\Event\SetDataEvent;
use Carbon\Carbon;
use ICal\ICal;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IcsSubscriber
{
    public function __construct(
        private CalendarService $calendarService,
        private UrlGeneratorInterface $router
    ) {
    }


    #[AsEventListener()]
    public function onCalendarSetData(SetDataEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();
//        $filters['icsUrl'] = 'http://www.castletonfire.com/ical.html?from=calendar&id=47525';

//        dd($filters, $calendar);
        if (!array_key_exists('icsUrl', $filters)) {
            return;
        }

        $icsUrl = $filters['icsUrl'];
        // for now...
        $feed = (new Feed())
            ->setUrl($icsUrl);
        $this->calendarService->parseUsingIcal($feed);

//        dd($feed->getBookings()->count());
//            $icsCalendar = $this->calendarService->loadByUrl($icsUrl);
//        dd($ical->getBookings());
//
//        try {
//        } catch (\Exception $exception) {
//            return;
//        }
//        $events = $icsCalendar->getEvents();
//        dd($events);
        foreach ($feed->getBookings() as $booking) {
            // this create the events with your data (here booking data) to fill calendar
            $event  = new Event(
                $booking->getTitle(),
                $booking->getBeginAt(),
                $booking->getEndAt() ?: null,

            );

            $event->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'green',
            ]);
            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($event);
        }
    }

}
