<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: 'service')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_service')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_service', length: 100)]
    private ?string $nomService = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(name: 'id_direction', referencedColumnName: 'id_direction', nullable: false)]
    private ?Direction $direction = null;

    /**
     * @var Collection<int, Poste>
     */
    #[ORM\OneToMany(targetEntity: Poste::class, mappedBy: 'service')]
    private Collection $postes;

    public function __construct()
    {
        $this->postes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomService(): ?string
    {
        return $this->nomService;
    }

    public function setNomService(string $nomService): static
    {
        $this->nomService = $nomService;

        return $this;
    }

    public function getDirection(): ?Direction
    {
        return $this->direction;
    }

    public function setDirection(?Direction $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return Collection<int, Poste>
     */
    public function getPostes(): Collection
    {
        return $this->postes;
    }

    public function addPoste(Poste $poste): static
    {
        if (!$this->postes->contains($poste)) {
            $this->postes->add($poste);
            $poste->setService($this);
        }

        return $this;
    }

    public function removePoste(Poste $poste): static
    {
        if ($this->postes->removeElement($poste)) {
            // set the owning side to null (unless already changed)
            if ($poste->getService() === $this) {
                $poste->setService(null);
            }
        }

        return $this;
    }
    
    public function __toString(): string
    {
        return (string) $this->nomService;
    }
}
