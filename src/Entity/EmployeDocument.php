<?php

namespace App\Entity;

use App\Repository\EmployeDocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeDocumentRepository::class)]
#[ORM\Table(name: 'employe_document')]
class EmployeDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_document')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_employe', referencedColumnName: 'id_employe', nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(name: 'type_document', length: 50, nullable: true)]
    private ?string $typeDocument = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fichier = null;

    #[ORM\Column(name: 'date_ajout', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAjout = null;

    public function __construct()
    {
        $this->dateAjout = new \DateTime();
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

    public function getTypeDocument(): ?string
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(?string $typeDocument): static
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): static
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }
}
