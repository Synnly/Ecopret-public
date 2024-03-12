<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $id_annonce = null;

    #[ORM\Column(length: 16380)]
    private ?string $dates_emprunt = null;

    #[ORM\Column]
    private ?int $id_emprunteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAnnonce(): ?Annonce
    {
        return $this->id_annonce;
    }

    public function getIdEmprunter(): ?int
    {
        return $this->id_emprunteur;
    }

    public function getDatesEmprunt(): ?string
    {
        return $this->dates_emprunt;
    }

    public function setIdAnnonce(Annonce $id_annonce): static
    {
        $this->id_annonce = $id_annonce;

        return $this;
    }

    public function setDatesEmprunt(string $dates_emprunt): static
    {
        $this->dates_emprunt = $dates_emprunt;

        return $this;
    }
    
    public function setIdEmprunteur(int $id_emprunteur): static
    {
        $this->id_emprunteur = $id_emprunteur;

        return $this;
    }
}