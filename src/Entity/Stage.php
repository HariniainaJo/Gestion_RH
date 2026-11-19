<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StageRepository::class)]
#[ORM\Table(name: 'stage')]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_stage')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stages')]
    #[ORM\JoinColumn(name: 'id_stagiaire', referencedColumnName: 'id_stagiaire', nullable: false)]
    private ?Stagiaire $stagiaire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_encadreur', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $encadreur = null;

    #[ORM\Column(name: 'date_debut', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(name: 'date_fin', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(name: 'type_stage', length: 50, nullable: true)]
    private ?string $typeStage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $sujet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStagiaire(): ?Stagiaire
    {
        return $this->stagiaire;
    }

    public function setStagiaire(?Stagiaire $stagiaire): static
    {
        $this->stagiaire = $stagiaire;

        return $this;
    }

    public function getEncadreur(): ?Employe
    {
        return $this->encadreur;
    }

    public function setEncadreur(?Employe $encadreur): static
    {
        $this->encadreur = $encadreur;

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

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTypeStage(): ?string
    {
        return $this->typeStage;
    }

    public function setTypeStage(?string $typeStage): static
    {
        $this->typeStage = $typeStage;

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): static
    {
        $this->sujet = $sujet;

        return $this;
    }
}
