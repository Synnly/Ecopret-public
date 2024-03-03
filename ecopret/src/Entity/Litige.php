<?php

namespace App\Entity;

use App\Repository\LitigeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LitigeRepository::class)]
class Litige
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    /*
     * 0 = En attente
     * 1 = En examen
     * 2 = Clos
     */
    private ?int $statut = null;

    #[ORM\ManyToOne(inversedBy: 'litiges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $plaignant = null;

    #[ORM\ManyToOne(inversedBy: 'litiges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $accuse = null;

    #[ORM\ManyToOne(inversedBy: 'litiges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Transaction $transaction = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getPlaignant(): ?Compte
    {
        return $this->plaignant;
    }

    public function setPlaignant(?Compte $plaignant): static
    {
        $this->plaignant = $plaignant;

        return $this;
    }

    public function getAccuse(): ?Compte
    {
        return $this->accuse;
    }

    public function setAccuse(?Compte $accuse): static
    {
        $this->accuse = $accuse;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): static
    {
        $this->transaction = $transaction;

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
}
