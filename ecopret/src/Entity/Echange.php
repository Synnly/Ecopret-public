<?php

namespace App\Entity;

use App\Repository\EchangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EchangeRepository::class)]
class Echange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $expeditaire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $destinataire = null;

    #[ORM\Column]
    //0 : Demandé; 1 : En cours; 2 : Refusé; 3 : Terminé;
    private ?int $etat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExpeditaire(): ?Annonce
    {
        return $this->expeditaire;
    }

    public function setExpeditaire(?Annonce $expeditaire): static
    {
        $this->expeditaire = $expeditaire;

        return $this;
    }

    public function getDestinataire(): ?Annonce
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Annonce $destinataire): static
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
