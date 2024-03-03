<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Monolog\Handler\Curl\Util;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Compte::class, mappedBy: 'transactions')]
    private Collection $comptes;

    #[ORM\ManyToOne(inversedBy: 'transaction')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $annonce = null;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: Litige::class, orphanRemoval: true)]
    private Collection $litiges;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestataire $Prestataire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $Client = null;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->litiges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Compte>
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): static
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes->add($compte);
            $compte->addTransaction($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): static
    {
        if ($this->comptes->removeElement($compte)) {
            $compte->removeTransaction($this);
        }

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

    /**
     * @return Collection<int, Litige>
     */
    public function getLitiges(): Collection
    {
        return $this->litiges;
    }

    public function addLitige(Litige $litige): static
    {
        if (!$this->litiges->contains($litige)) {
            $this->litiges->add($litige);
            $litige->setTransaction($this);
        }

        return $this;
    }

    public function removeLitige(Litige $litige): static
    {
        if ($this->litiges->removeElement($litige)) {
            // set the owning side to null (unless already changed)
            if ($litige->getTransaction() === $this) {
                $litige->setTransaction(null);
            }
        }

        return $this;
    }

    public function getPrestataire(): ?Prestataire
    {
        return $this->Prestataire;
    }

    public function setPrestataire(?Prestataire $Prestataire): static
    {
        $this->Prestataire = $Prestataire;

        return $this;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->Client;
    }

    public function setClient(?Utilisateur $Client): static
    {
        $this->Client = $Client;

        return $this;
    }
}
