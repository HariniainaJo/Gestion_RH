<?php

namespace App\Entity;

use App\Repository\PrimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: PrimeRepository::class)]
#[ORM\Table(name: 'prime')]
class Prime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_prime')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_prime', length: 100, nullable: true)]
    private ?string $nomPrime = null;

    #[ORM\Column(name: 'montant_max', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $montantMax = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $formule = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $collectif = false;

    #[ORM\ManyToMany(targetEntity: Kpi::class)]

    #[ORM\JoinTable(name: 'prime_kpi')]
    #[ORM\JoinColumn(name: 'id_prime', referencedColumnName: 'id_prime')]
    #[ORM\InverseJoinColumn(name: 'id_kpi', referencedColumnName: 'id_kpi')]
    private Collection $kpis;

    public function __construct() {
        $this->kpis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPrime(): ?string
    {
        return $this->nomPrime;
    }

    public function setNomPrime(?string $nomPrime): static
    {
        $this->nomPrime = $nomPrime;

        return $this;
    }

    public function getMontantMax(): ?string
    {
        return $this->montantMax;
    }

    public function setMontantMax(?string $montantMax): static
    {
        $this->montantMax = $montantMax;

        return $this;
    }

    public function getFormule(): ?string
    {
        return $this->formule;
    }

    public function setFormule(?string $formule): static
    {
        $this->formule = $formule;

        return $this;
    }

    public function isCollectif(): ?bool
    {
        return $this->collectif;
    }

    public function setCollectif(bool $collectif): static
    {
        $this->collectif = $collectif;

        return $this;
    }

    /**
     * @return Collection<int, Kpi>
     */
    public function getKpis(): Collection
    {
        return $this->kpis;
    }

    public function addKpi(Kpi $kpi): static
    {
        if (!$this->kpis->contains($kpi)) {
            $this->kpis->add($kpi);
        }

        return $this;
    }

    public function removeKpi(Kpi $kpi): static
    {
        $this->kpis->removeElement($kpi);

        return $this;
    }
}

