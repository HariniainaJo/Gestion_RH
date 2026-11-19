<?php

namespace App\Entity;

use App\Repository\PresenceRhRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenceRhRepository::class)]
#[ORM\Table(name: 'presence_rh')]
class PresenceRh
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_presence')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(name: 'type_evenement', length: 20)]
    private ?string $typeEvenement = null; // 'RETARD' or 'ABSENCE'

    #[ORM\Column(name: 'date_evenement', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $justification = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $justifie = false;

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

    public function getTypeEvenement(): ?string
    {
        return $this->typeEvenement;
    }

    public function setTypeEvenement(string $typeEvenement): static
    {
        $this->typeEvenement = $typeEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): static
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getJustification(): ?string
    {
        return $this->justification;
    }

    public function setJustification(?string $justification): static
    {
        $this->justification = $justification;

        return $this;
    }

    public function isJustifie(): ?bool
    {
        return $this->justifie;
    }

    public function setJustifie(bool $justifie): static
    {
        $this->justifie = $justifie;

        return $this;
    }
}
