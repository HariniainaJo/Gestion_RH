<?php

namespace App\Entity;

use App\Repository\KpiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KpiRepository::class)]
#[ORM\Table(name: 'kpi')]
class Kpi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_kpi')]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_kpi', length: 100, nullable: true)]
    private ?string $nomKpi = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'valeur_min', type: 'decimal', precision: 5, scale: 2, options: ['default' => 0])]
    private ?string $valeurMin = '0';

    #[ORM\Column(name: 'valeur_max', type: 'decimal', precision: 5, scale: 2, options: ['default' => 100])]
    private ?string $valeurMax = '100';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomKpi(): ?string
    {
        return $this->nomKpi;
    }

    public function setNomKpi(?string $nomKpi): static
    {
        $this->nomKpi = $nomKpi;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getValeurMin(): ?string
    {
        return $this->valeurMin;
    }

    public function setValeurMin(string $valeurMin): static
    {
        $this->valeurMin = $valeurMin;

        return $this;
    }

    public function getValeurMax(): ?string
    {
        return $this->valeurMax;
    }

    public function setValeurMax(string $valeurMax): static
    {
        $this->valeurMax = $valeurMax;

        return $this;
    }
}
