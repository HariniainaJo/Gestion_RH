<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prime_kpi')]
class PrimeKpi
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_prime', referencedColumnName: 'id_prime', nullable: false)]
    private ?Prime $prime = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_kpi', referencedColumnName: 'id_kpi', nullable: false)]
    private ?Kpi $kpi = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $poids = null;

    public function getPrime(): ?Prime
    {
        return $this->prime;
    }

    public function setPrime(?Prime $prime): static
    {
        $this->prime = $prime;

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

    public function getPoids(): ?string
    {
        return $this->poids;
    }

    public function setPoids(?string $poids): static
    {
        $this->poids = $poids;

        return $this;
    }
}
