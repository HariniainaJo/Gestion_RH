<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prime_groupe')]
class PrimeGroupe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_prime_groupe')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_groupe', referencedColumnName: 'id_groupe')]
    private ?Groupe $groupe = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_prime', referencedColumnName: 'id_prime')]
    private ?Prime $prime = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(name: 'montant_total', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $montantTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): static
    {
        $this->groupe = $groupe;

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

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(?string $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }
}
