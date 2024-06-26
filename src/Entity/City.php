<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Type('integer')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    #[Assert\Length(min:2, max:50, maxMessage: 'Ce champ peut contenir jusqu\'à 50 caractères !', minMessage: 'Ce champ peut contenir auminimum 2 caractères !')]
    #[Assert\NoSuspiciousCharacters]
    #[Assert\Type('string')]
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Ce champ nom est obligatoire !')]
    #[Assert\Length(min:5, max:5, maxMessage: 'Ce champ peut contenir jusqu\'à 5 caractères !', minMessage: 'Ce champ peut contenir au minimum 5 caractères !')]
    #[Assert\NoSuspiciousCharacters]
    #[Assert\Type('string')]
    private ?string $postalCode = null;

    #[ORM\OneToMany(targetEntity: Place::class, mappedBy: 'city')]
    private Collection $place;

    public function __construct()
    {
        $this->place = new ArrayCollection();
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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlace(): Collection
    {
        return $this->place;
    }

    public function addPlace(Place $place): static
    {
        if (!$this->place->contains($place)) {
            $this->place->add($place);
            $place->setCity($this);
        }

        return $this;
    }

    public function removePlace(Place $place): static
    {
        if ($this->place->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getCity() === $this) {
                $place->setCity(null);
            }
        }

        return $this;
    }
}
