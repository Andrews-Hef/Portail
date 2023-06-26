<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé !')]
/**
 * @ORM\Entity
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Commentaire::class)]
    private Collection $coms;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Abonnement $abonnement = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Role $role = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $is_verified = false;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $resetToken;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateFinAbonnement = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Tel = null;

    #[ORM\ManyToMany(targetEntity: Video::class, inversedBy: 'users')]
    private Collection $videoPref;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'users')]
    private Collection $categoriePref;

    public function __construct()
    {
        $this->coms = new ArrayCollection();
        $this->videoPref = new ArrayCollection();
        $this->categoriePref = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    /**
     * @return Collection<int, commentaire>
     */
    public function getComs(): Collection
    {
        return $this->coms;
    }

    public function addCom(commentaire $com): self
    {
        if (!$this->coms->contains($com)) {
            $this->coms->add($com);
            $com->setUsers($this);
        }

        return $this;
    }

    public function removeCom(commentaire $com): self
    {
        if ($this->coms->removeElement($com)) {
            // set the owning side to null (unless already changed)
            if ($com->getUsers() === $this) {
                $com->setUsers(null);
            }
        }

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(?Abonnement $abonnement): self
    {
        $this->abonnement = $abonnement;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getDateFinAbonnement(): ?\DateTimeInterface
    {
        return $this->dateFinAbonnement;
    }

    public function setDateFinAbonnement(?\DateTimeInterface $dateFinAbonnement): self
    {
        $this->dateFinAbonnement = $dateFinAbonnement;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(?string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->Tel;
    }

    public function setTel(?string $Tel): self
    {
        $this->Tel = $Tel;

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideoPref(): Collection
    {
        return $this->videoPref;
    }

    public function addVideoPref(Video $videoPref): self
    {
        if (!$this->videoPref->contains($videoPref)) {
            $this->videoPref->add($videoPref);
        }

        return $this;
    }

    public function removeVideoPref(Video $videoPref): self
    {
        $this->videoPref->removeElement($videoPref);

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategoriePref(): Collection
    {
        return $this->categoriePref;
    }

    public function addCategoriePref(Categorie $categoriePref): self
    {
        if (!$this->categoriePref->contains($categoriePref)) {
            $this->categoriePref->add($categoriePref);
        }

        return $this;
    }

    public function removeCategoriePref(Categorie $categoriePref): self
    {
        $this->categoriePref->removeElement($categoriePref);

        return $this;
    }
    
}
