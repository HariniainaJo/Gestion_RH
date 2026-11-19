<?php

namespace App\Entity;

use App\Repository\IrsaBaremeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IrsaBaremeRepository::class)]
#[ORM\Table(name: 'irsa_bareme')]
class IrsaBareme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_irsa')]
    private ?int $id = null;

    #[ORM\Column(name: 'salaire_min', type: 'decimal', precision: 10, scale: 2)]
    private ?string $salaireMin = null;

    #[ORM\Column(name: 'salaire_max', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $salaireMax = null;

    #[ORM\Column(type: 'decimal', precision: 7, scale: 4)]
    private ?string $taux = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalaireMin(): ?string
    {
        return $this->salaireMin;
    }

    public function setSalaireMin(string $salaireMin): static
    {
        $this->salaireMin = $salaireMin;

        return $this;
    }

    public function getSalaireMax(): ?string
    {
        return $this->salaireMax;
    }

    public function setSalaireMax(?string $salaireMax): static
    {
        $this->salaireMax = $salaireMax;

        return $this;
    }

    public function getTaux(): ?string
    {
        return $this->taux;
    }

    public function setTaux(string $taux): static
    {
        $this->taux = $taux;

        return $this;
    }
}

