<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleAbonnement = null;

    #[ORM\Column]
    private ?int $qualiteMax = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'abonnement', targetEntity: user::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleAbonnement(): ?string
    {
        return $this->libelleAbonnement;
    }

    public function setLibelleAbonnement(string $libelleAbonnement): self
    {
        $this->libelleAbonnement = $libelleAbonnement;

        return $this;
    }

    public function getQualiteMax(): ?int
    {
        return $this->qualiteMax;
    }

    public function setQualiteMax(int $qualiteMax): self
    {
        $this->qualiteMax = $qualiteMax;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(user $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setAbonnement($this);
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAbonnement() === $this) {
                $user->setAbonnement(null);
            }
        }

        return $this;
    }
}
