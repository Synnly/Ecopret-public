<?php

namespace App\Entity;

use App\Repository\PrestataireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestataireRepository::class)]
class Prestataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $noUtisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoUtisateur(): ?Utilisateur
    {
        return $this->noUtisateur;
    }

    public function setNoUtisateur(Utilisateur $noUtisateur): static
    {
        $this->noUtisateur = $noUtisateur;

        return $this;
    }
}
