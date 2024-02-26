<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\Clock\now;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    #[Assert\Length(min:2, max:100, maxMessage: 'Ce champ peut contenir jusqu\'à 100 caractères !', minMessage: 'Ce champ peut contenir auminimum 2 caractères !')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type("\DateTimeInterface")]
    #[Assert\GreaterThanOrEqual('today')]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    private ?\DateTimeInterface $startDatetime = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type("\DateTimeInterface")]
    #[Assert\LessThanOrEqual(propertyPath: 'startDatetime')]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    private ?\DateTimeInterface $limitRegisterDate = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    private ?int $maxRegisterQty = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eventInfos = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $registeredUser;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?User $organiser = null;

    public function __construct()
    {
        $this->registeredUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(\DateTimeInterface $startDatetime): static
    {
        $this->startDatetime = $startDatetime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLimitRegisterDate(): ?\DateTimeInterface
    {
        return $this->limitRegisterDate;
    }

    public function setLimitRegisterDate(\DateTimeInterface $limitRegisterDate): static
    {
        $this->limitRegisterDate = $limitRegisterDate;

        return $this;
    }

    public function getMaxRegisterQty(): ?int
    {
        return $this->maxRegisterQty;
    }

    public function setMaxRegisterQty(int $maxRegisterQty): static
    {
        $this->maxRegisterQty = $maxRegisterQty;

        return $this;
    }

    public function getEventInfos(): ?string
    {
        return $this->eventInfos;
    }

    public function setEventInfos(string $eventInfos): static
    {
        $this->eventInfos = $eventInfos;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getRegisteredUser(): Collection
    {
        return $this->registeredUser;
    }

    public function addRegisteredUser(User $registeredUser): static
    {
        if ($this->registeredUser->count() >= $this->maxRegisterQty) {
            throw new \Exception('Le nombre maximum d\'inscrit est atteint !');
        }

        if (!$this->registeredUser->contains($registeredUser)) {
            $this->registeredUser->add($registeredUser);
        }

        return $this;
    }

    public function removeRegisteredUser(User $registeredUser): static
    {
        $this->registeredUser->removeElement($registeredUser);
        return $this;
    }

    public function getOrganiser(): ?User
    {
        return $this->organiser;
    }

    public function setOrganiser(?User $organiser): static
    {
        $this->organiser = $organiser;

        return $this;
    }
}
