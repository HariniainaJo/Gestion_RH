<?php

namespace App\Entity;

use App\Repository\AxeNavetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AxeNavetteRepository::class)]
#[ORM\Table(name: 'axe_navette')]
class AxeNavette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_axe')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_axe', length: 100, nullable: true)]
    private ?string $nomAxe = null;

    #[ORM\Column(name: 'point_depart', length: 100, nullable: true)]
    private ?string $pointDepart = null;

    #[ORM\Column(name: 'point_arrivee', length: 100, nullable: true)]
    private ?string $pointArrivee = null;

    /**
     * @var Collection<int, Navette>
     */
    #[ORM\OneToMany(targetEntity: Navette::class, mappedBy: 'axe')]
    private Collection $navettes;

    public function __construct()
    {
        $this->navettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAxe(): ?string
    {
        return $this->nomAxe;
    }

    public function setNomAxe(?string $nomAxe): static
    {
        $this->nomAxe = $nomAxe;

        return $this;
    }

    public function getPointDepart(): ?string
    {
        return $this->pointDepart;
    }

    public function setPointDepart(?string $pointDepart): static
    {
        $this->pointDepart = $pointDepart;

        return $this;
    }

    public function getPointArrivee(): ?string
    {
        return $this->pointArrivee;
    }

    public function setPointArrivee(?string $pointArrivee): static
    {
        $this->pointArrivee = $pointArrivee;

        return $this;
    }

    /**
     * @return Collection<int, Navette>
     */
    public function getNavettes(): Collection
    {
        return $this->navettes;
    }

    public function addNavette(Navette $navette): static
    {
        if (!$this->navettes->contains($navette)) {
            $this->navettes->add($navette);
            $navette->setAxe($this);
        }

        return $this;
    }

    public function removeNavette(Navette $navette): static
    {
        if ($this->navettes->removeElement($navette)) {
            // set the owning side to null (unless already changed)
            if ($navette->getAxe() === $this) {
                $navette->setAxe(null);
            }
        }

        return $this;
    }
}
