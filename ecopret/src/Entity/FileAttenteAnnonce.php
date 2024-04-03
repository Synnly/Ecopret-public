<?php

namespace App\Entity;

use App\Repository\FileAttenteAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileAttenteAnnonceRepository::class)]
class FileAttenteAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne()]
    private ?Utilisateur $no_utilisateur = null;

    #[ORM\OneToOne()]
    private ?Annonce $no_annonce = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoUtilisateur(): ?Utilisateur
    {
        return $this->no_utilisateur;
    }

    public function setNoUtilisateur(Utilisateur $no_utilisateur): static
    {
        $this->no_utilisateur = $no_utilisateur;

        return $this;
    }

    public function getNoAnnonce(): ?Annonce
    {
        return $this->no_annonce;
    }

    public function setNoAnnonce(Annonce $no_annonce): static
    {
        $this->no_annonce = $no_annonce;

        return $this;
    }
}
