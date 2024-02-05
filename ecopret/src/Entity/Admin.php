<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: '`admin`')]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $noCompte = null;

    #[ORM\Column(length: 255)]

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoCompte(): ?Compte
    {
        return $this->noCompte;
    }

    public function setNoCompte(Compte $noCompte): static
    {
        $this->noCompte = $noCompte;

        return $this;
    }
}
