<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employe_kpi')]
class EmployeKpi
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_kpi', referencedColumnName: 'id_kpi', nullable: false)]
    private ?Kpi $kpi = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $mois = null;

    #[ORM\Id]
    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $valeur = null;

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

    public function getKpi(): ?Kpi
    {
        return $this->kpi;
    }

    public function setKpi(?Kpi $kpi): static
    {
        $this->kpi = $kpi;

        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(?string $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }
}
