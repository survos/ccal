<?php

namespace App\Entity;

use Survos\FieldBundle\Attribute\EntityMeta;
use Survos\FieldBundle\Attribute\RouteIdentity;
use Survos\FieldBundle\Entity\RouteParametersInterface;
use Survos\FieldBundle\Entity\RouteIdentityTrait;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\OrgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Routing\Route;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrgRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['Default', 'minimum', 'marking', 'browse', 'transitions', 'rp']],
    denormalizationContext: ['groups' => ["Default", "minimum", "browse"]],
)]
#[ApiFilter(OrderFilter::class, properties: ['marking', 'name', 'calCount'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ["marking" => "exact", 'ownerType' => 'exact', 'fullName' => 'partial', 'forkedFromId' => 'exact', 'isZip' => 'exact'])]

#[EntityMeta(icon: 'tabler:building-community', label: 'Organizations', group: 'Calendar', order: 10)]
#[RouteIdentity(field: 'slug')]
class Org implements RouteParametersInterface, \Stringable
{
    use RouteIdentityTrait;


    const PLACE_NEW = 'new';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['minimum', 'search'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['minimum', 'search'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['minimum', 'search'])]
    #[Gedmo\Slug(fields: ['name'])]
    private $slug;

    #[ORM\OneToMany(mappedBy: 'org', targetEntity: Cal::class, orphanRemoval: true)]
    private $calendars;

    #[ORM\Column(type: 'integer')]
    #[Groups(['minimum', 'search'])]
    private $calCount;

    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->marking = self::PLACE_NEW;
        $this->calCount = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Cal>
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Cal $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->setOrg($this);
            $this->calCount++;
        }

        return $this;
    }

    public function removeCalendar(Cal $calendar): self
    {
        if ($this->calendars->removeElement($calendar)) {
            $this->calCount--;
            // set the owning side to null (unless already changed)
            if ($calendar->getOrg() === $this) {
                $calendar->setOrg(null);
            }
        }

        return $this;
    }

    public function getCalCount(): ?int
    {
        return $this->calCount;
    }

    public function setCalCount(int $calCount): self
    {
        $this->calCount = $calCount;

        return $this;
    }

    #[Groups(['rp'])]
    public function getUniqueIdentifiers(): array
    {
        return ['orgId' => $this->getSlug()];
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
