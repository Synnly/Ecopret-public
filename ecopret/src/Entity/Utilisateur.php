<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $est_verifie = null;

    #[ORM\Column]
    private ?bool $est_gele = null;

    #[ORM\Column]
    private ?bool $paiement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_de_paiement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_deb_gel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_gel = null;

    #[ORM\Column]
    private ?bool $a_une_reduction = null;

    #[ORM\Column]
    private ?int $nb_florains = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $noCompte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEstVerifie(): ?bool
    {
        return $this->est_verifie;
    }

    public function setEstVerifie(bool $est_verifie): static
    {
        $this->est_verifie = $est_verifie;

        return $this;
    }

    public function isEstGele(): ?bool
    {
        return $this->est_gele;
    }

    public function setEstGele(bool $est_gele): static
    {
        $this->est_gele = $est_gele;

        return $this;
    }

    public function isPaiement(): ?bool
    {
        return $this->paiement;
    }

    public function setPaiement(bool $paiement): static
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getDateDePaiement(): ?\DateTimeInterface
    {
        return $this->date_de_paiement;
    }

    public function setDateDePaiement(?\DateTimeInterface $date_de_paiement): static
    {
        $this->date_de_paiement = $date_de_paiement;

        return $this;
    }

    public function getDateDebGel(): ?\DateTimeInterface
    {
        return $this->date_deb_gel;
    }

    public function setDateDebGel(?\DateTimeInterface $date_deb_gel): static
    {
        $this->date_deb_gel = $date_deb_gel;

        return $this;
    }

    public function getDateFinGel(): ?\DateTimeInterface
    {
        return $this->date_fin_gel;
    }

    public function setDateFinGel(?\DateTimeInterface $date_fin_gel): static
    {
        $this->date_fin_gel = $date_fin_gel;

        return $this;
    }

    public function isAUneReduction(): ?bool
    {
        return $this->a_une_reduction;
    }

    public function setAUneReduction(bool $a_une_reduction): static
    {
        $this->a_une_reduction = $a_une_reduction;

        return $this;
    }

    public function getNbFlorains(): ?int
    {
        return $this->nb_florains;
    }

    public function setNbFlorains(int $nb_florains): static
    {
        $this->nb_florains = $nb_florains;

        return $this;
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
