<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ORM\Table(name: 'groupe')]
class Groupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_groupe')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_groupe', length: 100, nullable: true)]
    private ?string $nomGroupe = null;

    #[ORM\Column(name: 'type_groupe', length: 20, nullable: true)]
    private ?string $typeGroupe = null;

    #[ORM\Column(name: 'date_debut', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: 'date_fin', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    /**
     * @var Collection<int, Employe>
     */
    #[ORM\ManyToMany(targetEntity: Employe::class)]
    #[ORM\JoinTable(name: 'groupe_employe')]
    #[ORM\JoinColumn(name: 'id_groupe', referencedColumnName: 'id_groupe')]
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

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(?string $nomGroupe): static
    {
        $this->nomGroupe = $nomGroupe;

        return $this;
    }

    public function getTypeGroupe(): ?string
    {
        return $this->typeGroupe;
    }

    public function setTypeGroupe(?string $typeGroupe): static
    {
        $this->typeGroupe = $typeGroupe;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

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
