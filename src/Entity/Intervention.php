<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
#[ORM\Table(name: 'HelpyInterventions')]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'InterventionNo')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    #[ORM\JoinColumn(
        name: 'InterventionPersonnelNo',
        referencedColumnName: 'LPersonnelNo',
        nullable: true,
        options: ['default' => 0],
    )]
    private ?Personnel $personnel = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    #[ORM\JoinColumn(
        name: 'InterventionType',
        referencedColumnName: 'InterventionType',
        nullable: true,
        options: ['default' => '']
    )]
    private ?InterventionType $type = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    #[ORM\JoinColumn(
        name: 'InterventionBénéficiaireNo',
        referencedColumnName: 'BénéficiaireNo',
        nullable: false,
        options: ['default' => 0],
    )]
    private ?Beneficiaire $beneficiaire = null;

    #[ORM\Column(
        name: 'InterventionMontantPayé',
        nullable: true,
        options: ['default' => 0]
    )]
    private ?float $montantPaye = null;

    #[ORM\Column(
        name: 'InterventionDateFacture',
        type: Types::DATETIME_MUTABLE,
        nullable: true,
        options: ['default' => '0000-00-00 00:00:00']
    )]
    private ?\DateTimeInterface $dateFacture = null;

    #[ORM\Column(
        name: 'InterventionMontantRéalisé',
        nullable: true,
        options: ['default' => false]
    )]
    private ?bool $montantRealise = null;

    #[ORM\Column(
        name: 'InterventionDateRéalisé',
        type: Types::DATETIME_MUTABLE,
        nullable: true,
        options: ['default' => '0000-00-00 00:00:00']
    )]
    private ?\DateTimeInterface $dateRealise = null;

    #[ORM\Column(
        name: 'InterventionDivers',
        type: Types::TEXT,
        length: 65535,
        nullable: true,
    )]
    private ?string $divers = null;

    #[ORM\Column(
        name: 'InterventionPieceNo',
        length: 20,
        nullable: true,
        options: ['default' => ''],
    )]
    private ?string $pieceNo = null;

    #[ORM\Column(
        name: 'InterventionExtraitNo',
        length: 20,
        nullable: true,
        options: ['default' => '']
    )]
    private ?string $extraitNo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnel(): ?Personnel
    {
        return $this->personnel;
    }

    public function setPersonnel(?Personnel $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getType(): ?InterventionType
    {
        return $this->type;
    }

    public function setType(?InterventionType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMontantPaye(): ?float
    {
        return $this->montantPaye;
    }

    public function setMontantPaye(?float $montantPaye): static
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->dateFacture;
    }

    public function setDateFacture(?\DateTimeInterface $dateFacture): static
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function isMontantRealise(): ?bool
    {
        return $this->montantRealise;
    }

    public function setMontantRealise(?bool $montantRealise): static
    {
        $this->montantRealise = $montantRealise;

        return $this;
    }

    public function getDateRealise(): ?\DateTimeInterface
    {
        return $this->dateRealise;
    }

    public function setDateRealise(?\DateTimeInterface $dateRealise): static
    {
        $this->dateRealise = $dateRealise;

        return $this;
    }

    public function getDivers(): ?string
    {
        return $this->divers;
    }

    public function setDivers(?string $divers): static
    {
        $this->divers = $divers;

        return $this;
    }

    public function getPieceNo(): ?string
    {
        return $this->pieceNo;
    }

    public function setPieceNo(?string $pieceNo): static
    {
        $this->pieceNo = $pieceNo;

        return $this;
    }

    public function getExtraitNo(): ?string
    {
        return $this->extraitNo;
    }

    public function setExtraitNo(?string $extraitNo): static
    {
        $this->extraitNo = $extraitNo;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): static
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }
}
