<?php

namespace App\Entity;

use App\Repository\ListeMotsClesAnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeMotsClesAnnonceRepository::class)]
class ListeMotsClesAnnonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?int $id_annonce = null;

    #[ORM\Column(length: 255)]
    private ?string $mot_cle = null;

    #[ORM\ManyToOne(inversedBy: 'mots_cles_annonce')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAnnonce(): ?int
    {
        return $this->id_annonce;
    }

    public function setIdAnnonce(int $id_annonce): static
    {
        $this->id_annonce = $id_annonce;

        return $this;
    }

    public function getMotCle(): ?string
    {
        return $this->mot_cle;
    }

    public function setMotCle(string $mot_cle): static
    {
        $this->mot_cle = $mot_cle;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): static
    {
        $this->annonce = $annonce;

        return $this;
    }
}
