<?php

namespace App\Entity;

use Survos\FieldBundle\Attribute\EntityMeta;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\{Entity, Column, Id, GeneratedValue};

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ApiResource]
#[EntityMeta(icon: 'tabler:calendar-check', label: 'Bookings', group: 'Calendar', order: 50)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $beginAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $endAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $durationInMinutes;

    #[ORM\ManyToOne(targetEntity: Feed::class, inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private $feed;

    // Can contain anything: array, objects, nested objects...
    #[Column(type: 'json_document', options: ['jsonb' => true])]
    public $icalEvent;


    public function __construct()
    {
        $this->beginAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginAt(): \DateTime
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTime $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTime $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDurationInMinutes(): ?int
    {
        return $this->durationInMinutes;
    }

    public function setDurationInMinutes(?int $durationInMinutes): self
    {
        $this->durationInMinutes = $durationInMinutes;
        if ($durationInMinutes) {
//            $endAt = \DateTimeImmutable::createFromMutable($this->getBeginAt());
            $endAt = clone $this->getBeginAt();

//            $endAt = \DateTimeImmutable::createFromMutable($beginAt);

            $date = date_create('2000-01-01');
            date_add($date, date_interval_create_from_date_string('10 days'));

            $endAt->add(new \DateInterval($format = sprintf('P%dD', $durationInMinutes)));
//                assert($endAt <> $this->getBeginAt());

//            $this->setEndAt(\DateTime::createFromImmutable($endAt));
            assert($this->getEndAt() <> $this->getBeginAt(), $format);
        }

        return $this;
    }

    public function getFeed(): ?Feed
    {
        return $this->feed;
    }

    public function setFeed(?Feed $feed): self
    {
        $this->feed = $feed;

        return $this;
    }
}
