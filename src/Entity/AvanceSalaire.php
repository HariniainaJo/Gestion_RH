<?php

namespace App\Entity;

use App\Repository\AvanceSalaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvanceSalaireRepository::class)]
#[ORM\Table(name: 'avance_salaire')]
class AvanceSalaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_avance')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(name: 'date_demande', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private ?string $statut = 'EN_ATTENTE';

    #[ORM\Column(name: 'mois_deduction', nullable: true)]
    private ?int $moisDeduction = null;

    #[ORM\Column(name: 'annee_deduction', nullable: true)]
    private ?int $anneeDeduction = null;

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

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;
        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): static
    {
        $this->dateDemande = $dateDemande;
        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getMoisDeduction(): ?int
    {
        return $this->moisDeduction;
    }

    public function setMoisDeduction(?int $moisDeduction): static
    {
        $this->moisDeduction = $moisDeduction;
        return $this;
    }

    public function getAnneeDeduction(): ?int
    {
        return $this->anneeDeduction;
    }

    public function setAnneeDeduction(?int $anneeDeduction): static
    {
        $this->anneeDeduction = $anneeDeduction;
        return $this;
    }
}
