<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Type('integer')]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    #[Assert\Length(min:2, max:30, maxMessage: 'Ce champ peut contenir jusqu\'à 30 caractères !', minMessage: 'Ce champ peut contenir auminimum 2 caractères !')]
    #[Assert\NoSuspiciousCharacters]
    #[Assert\Type('string')]
    private ?string $name_site = null;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'site')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameSite(): ?string
    {
        return $this->name_site;
    }

    public function setNameSite(string $name_site): static
    {
        $this->name_site = $name_site;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setSite($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getSite() === $this) {
                $event->setSite(null);
            }
        }

        return $this;
    }
}
