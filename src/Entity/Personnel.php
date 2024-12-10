<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PersonnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonnelRepository::class)]
#[ORM\Table(name: 'HelpyLPersonnel')]
#[ORM\Index(columns: ['LPersonnelNom', 'LPersonnelPrénom'], name: 'NOMPRENOM')]
class Personnel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'LPersonnelNo', type: 'integer')]
    private int $id;

    #[ORM\Column(   
        name: 'LPersonnelNom',
        type: 'string',
        length: 50,
        nullable: true,
        options: ['default' => '']
    )]
    private string $nom;

    #[ORM\Column(
        name: 'LPersonnelPrénom',
        type: 'string',
        length: 50,
        nullable: true,
        options: ['default' => '']
    )]
    private string $prenom;

    #[ORM\Column(name: 'LPersonnelFournisseur', type: 'boolean', nullable: false, options: ['default' => false])]
    private bool $fournisseur;

    #[ORM\Column(
        name: 'LPersonnelCompteBanque',
        type: 'string',
        length: 20,
        nullable: true,
        options: ['default' => '0'],
    )]
    private string $compteBanque;

    #[ORM\Column(name: 'LPersonnelSoldeSMAP', nullable: true, options: ['default' => 0])]
    private int $soldeSmap;

    /**
     * @var Collection<int, Beneficiaire>
     */
    #[ORM\OneToMany(targetEntity: Beneficiaire::class, mappedBy: 'personnel', orphanRemoval: true)]
    private Collection $beneficiaires;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\OneToMany(targetEntity: Intervention::class, mappedBy: 'personnel')]
    private Collection $interventions;

    public function __construct()
    {
        $this->beneficiaires = new ArrayCollection();
        $this->interventions = new ArrayCollection();
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFournisseur(): bool
    {
        return $this->fournisseur;
    }

    public function setFournisseur(bool $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getCompteBanque(): string
    {
        return $this->compteBanque;
    }

    public function setCompteBanque(string $compteBanque): Personnel
    {
        $this->compteBanque = $compteBanque;

        return $this;
    }

    public function getSoldeSmap(): int
    {
        return $this->soldeSmap;
    }

    public function setSoldeSmap(int $soldeSmap): Personnel
    {
        $this->soldeSmap = $soldeSmap;

        return $this;
    }

    /**
     * @return Collection<int, Beneficiaire>
     */
    public function getBeneficiaires(): Collection
    {
        return $this->beneficiaires;
    }

    public function addBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if (!$this->beneficiaires->contains($beneficiaire)) {
            $this->beneficiaires->add($beneficiaire);
            $beneficiaire->setPersonnel($this);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if ($this->beneficiaires->removeElement($beneficiaire)) {
            if ($beneficiaire->getPersonnel() === $this) {
                $beneficiaire->setPersonnel(null);
            }
        }

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
            $intervention->setPersonnel($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            if ($intervention->getPersonnel() === $this) {
                $intervention->setPersonnel(null);
            }
        }

        return $this;
    }
}
