<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
#[UniqueEntity('username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Le champ email est obligatoire !')]
    #[Assert\Email(message: 'Un email est attendu ici !')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Le champ password est obligatoire !')]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Le champ nom est obligatoire !')]
    #[Assert\Length(min: 2, max: 30, maxMessage: 'Ce champ peut contenir jusqu\'à 30 caractères !', minMessage: 'Ce champ peut contenir auminimum 2 caractères !')]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Le champ nom est obligatoire !')]
    #[Assert\Length(min: 2, max: 30, maxMessage: 'Ce champ peut contenir jusqu\'à 30 caractères !', minMessage: 'Ce champ peut contenir auminimum 2 caractères !')]

    private ?string $firstname = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank]
    #[Assert\NotNull(message: 'Le champ nom est obligatoire !')]
    #[Assert\Length(min: 2, max: 30, maxMessage: 'Ce champ peut contenir jusqu\'à 30 caractères !', minMessage: 'Ce champ peut contenir auminimum 2 caractères !')]
    private ?string $phone = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profilePicture = null;

    /**
     * @Vich\UploadableField(mapping="user_profile_pictures", fileNameProperty="profilePicture")
     * @Assert\File(maxSize="5M", mimeTypes={"image/jpeg", "image/png", "image/gif"})
     */
    private ?File $profilePictureFile = null;


    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'isRegister')]
    private Collection $events;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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
            $event->addIsRegister($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeIsRegister($this);
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }

    public function getProfilePictureFile(): ?File
    {
        return $this->profilePictureFile;
    }

    public function setProfilePictureFile(?File $profilePictureFile): void
    {
        $this->profilePictureFile = $profilePictureFile;
    }
}
