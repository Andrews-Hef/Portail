<?php

namespace App\Entity;

use App\Repository\TypeVideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeVideoRepository::class)]
class TypeVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleTypeVideo = null;

    #[ORM\OneToMany(mappedBy: 'typeVideo', targetEntity: Video::class)]
    private Collection $typesrelationvideo;

    public function __construct()
    {
        $this->typesrelationvideo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleTypeVideo(): ?string
    {
        return $this->libelleTypeVideo;
    }

    public function setLibelleTypeVideo(string $libelleTypeVideo): self
    {
        $this->libelleTypeVideo = $libelleTypeVideo;

        return $this;
    }

    /**
     * @return Collection<int, video>
     */
    public function getTypesrelationvideo(): Collection
    {
        return $this->typesrelationvideo;
    }

    public function addTypesrelationvideo(video $typesrelationvideo): self
    {
        if (!$this->typesrelationvideo->contains($typesrelationvideo)) {
            $this->typesrelationvideo->add($typesrelationvideo);
            $typesrelationvideo->setTypeVideo($this);
        }

        return $this;
    }

    public function removeTypesrelationvideo(video $typesrelationvideo): self
    {
        if ($this->typesrelationvideo->removeElement($typesrelationvideo)) {
            // set the owning side to null (unless already changed)
            if ($typesrelationvideo->getTypeVideo() === $this) {
                $typesrelationvideo->setTypeVideo(null);
            }
        }

        return $this;
    }
}
