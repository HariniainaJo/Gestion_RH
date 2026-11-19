<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'employe_prime')]
class EmployePrime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_employe_prime')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe')]
    private ?Employe $employe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_prime', referencedColumnName: 'id_prime')]
    private ?Prime $prime = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(name: 'montant_calcule', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $montantCalcule = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $valide = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

    public function getPrime(): ?Prime
    {
        return $this->prime;
    }

    public function setPrime(?Prime $prime): static
    {
        $this->prime = $prime;

        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(?int $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMontantCalcule(): ?string
    {
        return $this->montantCalcule;
    }

    public function setMontantCalcule(?string $montantCalcule): static
    {
        $this->montantCalcule = $montantCalcule;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }
}
