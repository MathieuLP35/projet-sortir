<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDatetime = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $limitRegisterDate = null;

    #[ORM\Column]
    private ?int $maxRegisterQty = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $eventInfos = null;

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
}
