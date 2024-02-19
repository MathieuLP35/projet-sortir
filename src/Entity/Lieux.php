<?php

namespace App\Entity;

use App\Repository\LieuxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuxRepository::class)]
class Lieux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "no_lieu", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "nom_lieu", length: 30)]
    private ?string $nomLieu = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $rue = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(name: "villes_no_ville", type: "integer")]
    private ?int $villesNoVille = null;

    #[ORM\ManyToOne(targetEntity: Villes::class, inversedBy: "lieux")]
    #[ORM\JoinColumn(name: "villes_no_ville", referencedColumnName: "no_ville")]
    private ?Villes $ville = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLieu(): ?string
    {
        return $this->nomLieu;
    }

    public function setNomLieu(string $nomLieu): static
    {
        $this->nomLieu = $nomLieu;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVillesNoVille(): ?int
    {
        return $this->villesNoVille;
    }

    public function setVillesNoVille(int $villesNoVille): static
    {
        $this->villesNoVille = $villesNoVille;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
}