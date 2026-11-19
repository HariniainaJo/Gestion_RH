<?php

namespace App\Entity;

use App\Repository\NavetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NavetteRepository::class)]
#[ORM\Table(name: 'navette')]
class Navette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_navette')]
    private ?int $id = null;

    #[ORM\Column(name: 'code_navette', length: 50, nullable: true)]
    private ?string $codeNavette = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacite = null;

    #[ORM\ManyToOne(inversedBy: 'navettes')]
    #[ORM\JoinColumn(name: 'id_axe', referencedColumnName: 'id_axe', nullable: false)]
    private ?AxeNavette $axe = null;

    /**
     * @var Collection<int, Employe>
     */
    #[ORM\ManyToMany(targetEntity: Employe::class)]
    #[ORM\JoinTable(name: 'employe_navette')]
    #[ORM\JoinColumn(name: 'id_navette', referencedColumnName: 'id_navette')]
    #[ORM\InverseJoinColumn(name: 'id_employe', referencedColumnName: 'id_employe')]
    private Collection $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeNavette(): ?string
    {
        return $this->codeNavette;
    }

    public function setCodeNavette(?string $codeNavette): static
    {
        $this->codeNavette = $codeNavette;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getAxe(): ?AxeNavette
    {
        return $this->axe;
    }

    public function setAxe(?AxeNavette $axe): static
    {
        $this->axe = $axe;

        return $this;
    }

    /**
     * @return Collection<int, Employe>
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): static
    {
        if (!$this->employes->contains($employe)) {
            $this->employes->add($employe);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): static
    {
        $this->employes->removeElement($employe);

        return $this;
    }
}
