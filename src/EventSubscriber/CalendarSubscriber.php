<?php

namespace App\EventSubscriber;

use App\Repository\EventRepository;
use App\Entity\Event;
use CalendarBundle\Entity\Event as CalendarBundleEvent;
use CalendarBundle\Event\SetDataEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber
{
    public function __construct(
        private EventRepository $eventRepository,
        private UrlGeneratorInterface $router
    ) {
    }

    #[AsEventListener()]
    public function onCalendarSetData(SetDataEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();


        // Modify the query to fit to your entity and needs
        // Change event.beginAt by your start date property
        /** @var Event[] $events */
        $events = $this->eventRepository
            ->createQueryBuilder('event')
            ->where('event.startTime BETWEEN :start and :end OR event.endTime BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($events as $event) {
            // this create the events with your data (here event data) to fill calendar
            $bookingEvent = new CalendarBundleEvent(
                $event->getCal()->getName() . '/' . $event->getTitle(),
                $event->getStartTime(),
                $event->getEndTime() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $bookingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $bookingEvent->addOption(
                'url',
                $this->router->generate('event_show', $event->getRP())
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($bookingEvent);
        }
    }
}
