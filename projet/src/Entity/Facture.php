<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    //#[Assert\NotBlank(message: 'Le numéro de facture est obligatoire.')]
    private ?string $numeroFacture = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: 'La date de facturation est obligatoire.')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $dateFacturation = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: 'Le montant est obligatoire.')]
    #[Assert\GreaterThan(value: 0, message: 'Le montant doit être supérieur à 0.')]
    private ?float $montant = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['Payée', 'Partiellement payée', 'Non payée'], message: 'Choisissez un état valide.')]
    private ?string $etat = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $note = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    //#[Assert\NotNull(message: 'Le client est obligatoire.')]
    private ?Client $client = null;

    public function getId(): ?int { return $this->id; }

    public function getNumeroFacture(): ?string { return $this->numeroFacture; }
    public function setNumeroFacture(string $numeroFacture): self { $this->numeroFacture = $numeroFacture; return $this; }

    public function getDateFacturation(): ?\DateTimeInterface { return $this->dateFacturation; }
    public function setDateFacturation(\DateTimeInterface $dateFacturation): self { $this->dateFacturation = $dateFacturation; return $this; }

    public function getMontant(): ?float { return $this->montant; }
    public function setMontant(float $montant): self { $this->montant = $montant; return $this; }

    public function getEtat(): ?string { return $this->etat; }
    public function setEtat(string $etat): self { $this->etat = $etat; return $this; }

    public function getNote(): ?string { return $this->note; }
    public function setNote(?string $note): self { $this->note = $note; return $this; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): self { $this->client = $client; return $this; }
}
