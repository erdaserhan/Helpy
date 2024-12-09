<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BeneficiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BeneficiaireRepository::class)]
#[ORM\Table(name: 'HelpyBénéficiaires')]
class Beneficiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'BénéficiaireNo', type: 'integer')]
    private int $id;

    #[ORM\Column(
        name: 'BénéficiaireNomPrénom',
        type: 'string',
        length: 50,
        nullable: true,
        options: ['default' => ''],
    )]
    private string $nomPrenom;

    #[ORM\ManyToOne(inversedBy: 'beneficiaires')]
    #[ORM\JoinColumn(
        name: 'BénéficiairePersonnelNo',
        referencedColumnName: 'LPersonnelNo',
        nullable: true,
        options: ['default' => 0],
    )]
    private ?Personnel $personnel = null;

    #[ORM\Column(
        name: 'BénéficiaireDateNaissance',
        type: 'datetime',
        options: ['default' => '0000-00-00 00:00:00'],
    )]
    #[Assert\NotBlank]
    private ?\DateTime $dateNaissance = null;

    #[ORM\Column(
        name: 'BénéficiaireLien',
        type: 'string',
        length: 50,
        nullable: true,
        options: ['default' => ''],
    )]
    private string $lien;

    private int $age;

    #[ORM\Column(
        name: 'BénéficiaireDateLunette',
        type: Types::DATETIME_MUTABLE,
        nullable: true,
        options: ['default' => '0000-00-00 00:00:00'],
    )]
    private ?\DateTimeInterface $dateLunette = null;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\OneToMany(targetEntity: Intervention::class, mappedBy: 'beneficiaire')]
    private Collection $interventions;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
    }

    public function getNomPrenom(): string
    {
        return $this->nomPrenom;
    }

    public function getDateNaissance(): \DateTime|null
    {
        if (\DateTime::createFromFormat('Y-m-d', $this->dateNaissance?->format('Y-m-d') ?? '') === false) {
            return null;
        } else {
            return $this->dateNaissance;
        }
    }

    public function getAge(string $dateDeReference = null): int|null
    {
        if ($this->getDateNaissance() === null) {
            return null;
        }
        if ($dateDeReference === null) {
            $dateDeReference = 'now';
        }
        $dateRef = strtotime($dateDeReference);
        $now = getdate($dateRef === false ? null : $dateRef);
        $annee = $now['year'];
        $mois = $now['mon'];
        $jour = $now['mday'];
        $date = $this->dateNaissance?->format('Y-m-d') ?? '';
        $anneenaissance = substr($date, 0, 4);
        $moisnaissance = substr($date, 5, 2);
        $journaissance = substr($date, 8, 2);
        $duree = $annee - intval($anneenaissance);
        if ($mois < $moisnaissance) {
            $duree = $duree - 1;
        } elseif (($mois == $moisnaissance)) {
            if ($jour < $journaissance) {
                $duree = $duree - 1;
            }
        }
        $this->age = $duree;
        return $this->age;
    }

    public function getLien(): string
    {
        return $this->lien;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function setDateNaissance(?\DateTime $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
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

    public function getDateLunette(): ?\DateTimeInterface
    {
        return $this->dateLunette;
    }

    public function setDateLunette(\DateTimeInterface|null $dateLunette): static
    {
        $this->dateLunette = $dateLunette;

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            if ($intervention->getBeneficiaire() === $this) {
                $intervention->setBeneficiaire(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId();
    }
}
