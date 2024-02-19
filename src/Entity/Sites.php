<?php

namespace App\Entity;

use App\Repository\SitesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SitesRepository::class)]
class Sites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "no_site", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name_site = null;

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
}
