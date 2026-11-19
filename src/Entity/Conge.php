<?php

namespace App\Entity;

use App\Repository\CongeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CongeRepository::class)]
#[ORM\Table(name: 'conge')]
class Conge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_conge')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(name: 'date_debut', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: 'date_fin', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: 'type_conge', length: 50, nullable: true)]
    private ?string $typeConge = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $motif = null;

    #[ORM\Column(length: 20, options: ['default' => 'EN_ATTENTE'])]
    private ?string $statut = 'EN_ATTENTE';

    #[ORM\Column(name: 'date_demande', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(name: 'date_validation', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateValidation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'valide_par', referencedColumnName: 'id_user', nullable: true)]
    private ?Utilisateur $validePar = null;

    public function __construct()
    {
        $this->dateDemande = new \DateTime();
    }

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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTypeConge(): ?string
    {
        return $this->typeConge;
    }

    public function setTypeConge(?string $typeConge): static
    {
        $this->typeConge = $typeConge;

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

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(?\DateTimeInterface $dateDemande): static
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): static
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    public function getValidePar(): ?Utilisateur
    {
        return $this->validePar;
    }

    public function setValidePar(?Utilisateur $validePar): static
    {
        $this->validePar = $validePar;

        return $this;
    }
}
