<?php

namespace App\Entity;

use App\Repository\PayeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayeRepository::class)]
#[ORM\Table(name: 'paye')]
class Paye
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_paye')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(nullable: true)]
    private ?int $mois = null;

    #[ORM\Column(nullable: true)]
    private ?int $annee = null;

    #[ORM\Column(name: 'salaire_brut', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $salaireBrut = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $retenue = null;

    #[ORM\Column(name: 'total_prime', type: 'decimal', precision: 10, scale: 2, options: ['default' => 0])]
    private ?string $totalPrime = '0';

    #[ORM\Column(name: 'salaire_net', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $salaireNet = null;

    #[ORM\Column(name: 'date_paiement', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePaiement = null;

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

    public function getSalaireBrut(): ?string
    {
        return $this->salaireBrut;
    }

    public function setSalaireBrut(?string $salaireBrut): static
    {
        $this->salaireBrut = $salaireBrut;

        return $this;
    }

    public function getRetenue(): ?string
    {
        return $this->retenue;
    }

    public function setRetenue(?string $retenue): static
    {
        $this->retenue = $retenue;

        return $this;
    }

    public function getTotalPrime(): ?string
    {
        return $this->totalPrime;
    }

    public function setTotalPrime(string $totalPrime): static
    {
        $this->totalPrime = $totalPrime;

        return $this;
    }

    public function getSalaireNet(): ?string
    {
        return $this->salaireNet;
    }

    public function setSalaireNet(?string $salaireNet): static
    {
        $this->salaireNet = $salaireNet;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): static
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }
}
