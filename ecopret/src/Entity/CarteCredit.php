<?php

namespace App\Entity;

use App\Repository\CarteCreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarteCreditRepository::class)]
class CarteCredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 16)]
    private ?string $numero_carte = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_expiration = null;

    #[ORM\Column]
    private ?int $code_cvv = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_carte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroCarte(): ?int
    {
        return $this->numero_carte;
    }

    public function setNumeroCarte(int $numero_carte): static
    {
        $this->numero_carte = $numero_carte;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(\DateTimeInterface $date_expiration): static
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getCodeCvv(): ?int
    {
        return $this->code_cvv;
    }

    public function setCodeCvv(int $code_cvv): static
    {
        $this->code_cvv = $code_cvv;

        return $this;
    }

    public function getNomCarte(): ?string
    {
        return $this->nom_carte;
    }

    public function setNomCarte(string $nom_carte): static
    {
        $this->nom_carte = $nom_carte;

        return $this;
    }
}
