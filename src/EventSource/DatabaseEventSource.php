<?php

declare(strict_types=1);

namespace App\EventSource;

use App\Repository\FeedRepository;
use ICal\ICal;
use Psr\Log\LoggerInterface;
use Survos\UxCalendarBundle\Contract\EventSourceInterface;
use Survos\UxCalendarBundle\Dto\CalendarEvent;

/**
 * Aggregates events from every Feed in the database, tagging each event with its
 * feed slug + color. This is ccal's DB-backed replacement for the bundle's
 * config (YAML) driven ConfiguredIcsSource. Registered as a tagged
 * `survos.ux_calendar.event_source` via services.yaml _instanceof.
 */
final class DatabaseEventSource implements EventSourceInterface
{
    public function __construct(
        private readonly FeedRepository $feedRepository,
        private readonly ?LoggerInterface $logger = null,
    ) {
    }

    public function supports(array $context = []): bool
    {
        // Contribute DB feeds unless the caller requested a single ad-hoc icsUrl.
        return empty($context['icsUrl']);
    }

    /**
     * @return iterable<CalendarEvent>
     */
    public function getEvents(?\DateTimeInterface $start = null, ?\DateTimeInterface $end = null, array $context = []): iterable
    {
        $events = [];

        foreach ($this->feedRepository->findAll() as $feed) {
            $url = $feed->getUrl();
            if (!$url) {
                continue;
            }

            try {
                $ical = new ICal($url, ['skipRecurrence' => false]);
            } catch (\Throwable $e) {
                $this->logger?->warning('ccal: failed to load feed "{slug}": {message}', [
                    'slug' => $feed->getSlug(),
                    'message' => $e->getMessage(),
                    'url' => $url,
                ]);
                continue;
            }

            $parsed = ($start && $end)
                ? $ical->eventsFromRange($start->format('c'), $end->format('c'))
                : $ical->events();

            $sourceId = $feed->getSlug();
            $label = $feed->title ?? $feed->getSlug();
            $color = $feed->color;

            foreach ($parsed as $event) {
                $events[] = $this->mapEvent($sourceId, $label, $color, $event);
            }
        }

        return $events;
    }

    private function mapEvent(string $sourceId, string $label, ?string $color, object $event): CalendarEvent
    {
        $start = $event->dtstart instanceof \DateTimeInterface
            ? $event->dtstart
            : new \DateTimeImmutable((string) $event->dtstart);

        $end = null;
        if (isset($event->dtend) && '' !== (string) $event->dtend) {
            $end = $event->dtend instanceof \DateTimeInterface
                ? $event->dtend
                : new \DateTimeImmutable((string) $event->dtend);
        }

        $allDay = 8 === strlen((string) $event->dtstart);

        $metadata = ['sourceId' => $sourceId, 'sourceLabel' => $label];
        if (null !== $color && '' !== $color) {
            $metadata['sourceColor'] = $color;
            $metadata['backgroundColor'] = $color;
            $metadata['borderColor'] = $color;
        }

        return new CalendarEvent(
            id: isset($event->uid) ? (string) $event->uid : null,
            title: (string) ($event->summary ?? 'Untitled event'),
            start: $start,
            end: $end,
            allDay: $allDay,
            description: isset($event->description) ? (string) $event->description : null,
            location: isset($event->location) ? (string) $event->location : null,
            metadata: $metadata,
        );
    }
}
