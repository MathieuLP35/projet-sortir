<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
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
    private ?string $libelle = null;

    // CONSTANTE
    public const CREATED = 'Créée';
    public const OPEN = 'Ouvert';
    public const CLOSED = 'Clôturée';
    public const IN_PROGRESS = 'Activité en cours';
    public const PAST = 'Passée';
    public const CANCELLED = 'Annulée';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
