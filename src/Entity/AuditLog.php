<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'audit_log')]
class AuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_log')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    private ?Utilisateur $user = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $action = null;

    #[ORM\Column(name: 'table_concernee', length: 100, nullable: true)]
    private ?string $tableConcernee = null;

    #[ORM\Column(name: 'id_enregistrement', nullable: true)]
    private ?int $idEnregistrement = null;

    #[ORM\Column(name: 'date_action', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAction = null;

    #[ORM\Column(name: 'adresse_ip', length: 50, nullable: true)]
    private ?string $adresseIp = null;

    public function __construct()
    {
        $this->dateAction = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getTableConcernee(): ?string
    {
        return $this->tableConcernee;
    }

    public function setTableConcernee(?string $tableConcernee): static
    {
        $this->tableConcernee = $tableConcernee;

        return $this;
    }

    public function getIdEnregistrement(): ?int
    {
        return $this->idEnregistrement;
    }

    public function setIdEnregistrement(?int $idEnregistrement): static
    {
        $this->idEnregistrement = $idEnregistrement;

        return $this;
    }

    public function getDateAction(): ?\DateTimeInterface
    {
        return $this->dateAction;
    }

    public function setDateAction(?\DateTimeInterface $dateAction): static
    {
        $this->dateAction = $dateAction;

        return $this;
    }

    public function getAdresseIp(): ?string
    {
        return $this->adresseIp;
    }

    public function setAdresseIp(?string $adresseIp): static
    {
        $this->adresseIp = $adresseIp;

        return $this;
    }
}
