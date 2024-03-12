<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_annonce = null;

    #[ORM\Column(length: 65535)]
    private ?string $disponibilite = null;

    #[ORM\Column]
    private ?bool $est_rendu = null;

    #[ORM\Column]
    private ?bool $est_en_litige = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_annonce = null;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: ListeDatesAnnonce::class)]
    private Collection $dates_annonce;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: ListeMotsClesAnnonce::class)]
    private Collection $mots_cles_annonce;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Transaction::class)]
    private Collection $transaction;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestataire $prestataire = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $prix = null;

    public function __construct()
    {
        $this->dates_annonce = new ArrayCollection();
        $this->mots_cles_annonce = new ArrayCollection();
        $this->transaction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAnnonce(): ?string
    {
        return $this->nom_annonce;
    }

    public function setNomAnnonce(string $nom_annonce): static
    {
        $this->nom_annonce = $nom_annonce;

        return $this;
    }

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function isEstRendu(): ?bool
    {
        return $this->est_rendu;
    }

    public function setEstRendu(bool $est_rendu): static
    {
        $this->est_rendu = $est_rendu;

        return $this;
    }

    public function isEstEnLitige(): ?bool
    {
        return $this->est_en_litige;
    }

    public function setEstEnLitige(bool $est_en_litige): static
    {
        $this->est_en_litige = $est_en_litige;

        return $this;
    }

    public function getImageAnnonce(): ?string
    {
        return $this->image_annonce;
    }

    public function setImageAnnonce(?string $image_annonce): static
    {
        $this->image_annonce = $image_annonce;

        return $this;
    }

    /**
     * @return Collection<int, ListeDatesAnnonce>
     */
    public function getDatesAnnonce(): Collection
    {
        return $this->dates_annonce;
    }

    public function addDatesAnnonce(ListeDatesAnnonce $datesAnnonce): static
    {
        if (!$this->dates_annonce->contains($datesAnnonce)) {
            $this->dates_annonce->add($datesAnnonce);
            $datesAnnonce->setAnnonce($this);
        }

        return $this;
    }

    public function removeDatesAnnonce(ListeDatesAnnonce $datesAnnonce): static
    {
        if ($this->dates_annonce->removeElement($datesAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($datesAnnonce->getAnnonce() === $this) {
                $datesAnnonce->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeMotsClesAnnonce>
     */
    public function getMotsClesAnnonce(): Collection
    {
        return $this->mots_cles_annonce;
    }

    public function addMotsClesAnnonce(ListeMotsClesAnnonce $motsClesAnnonce): static
    {
        if (!$this->mots_cles_annonce->contains($motsClesAnnonce)) {
            $this->mots_cles_annonce->add($motsClesAnnonce);
            $motsClesAnnonce->setAnnonce($this);
        }

        return $this;
    }

    public function removeMotsClesAnnonce(ListeMotsClesAnnonce $motsClesAnnonce): static
    {
        if ($this->mots_cles_annonce->removeElement($motsClesAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($motsClesAnnonce->getAnnonce() === $this) {
                $motsClesAnnonce->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction->add($transaction);
            $transaction->setAnnonce($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAnnonce() === $this) {
                $transaction->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getPrestataire(): ?Prestataire
    {
        return $this->prestataire;
    }

    public function setPrestataire(?Prestataire $prestataire): static
    {
        $this->prestataire = $prestataire;

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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
}
