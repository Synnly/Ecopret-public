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

    #[ORM\Column(length: 14500)]
    private ?string $disponibilite = null;

    #[ORM\Column]
    private ?bool $est_rendu = null;

    #[ORM\Column]
    private ?bool $est_en_litige = null;

    #[ORM\Column]
    private ?bool $est_un_emprunt = null;

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

    public function getEstUnEmprunt(): ?bool
    {
        return $this->est_un_emprunt;
    }

    public function setEstUnEmprunt(bool $isEmprunt): static
    {
        $this->est_un_emprunt = $isEmprunt;

        return $this;
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

    public function getDisponibiliteLisible(): ?array
    {
        if($this->disponibilite != "") {
            $this->disponibilite = substr($this->disponibilite,0,-1);

            $dispoTab = explode('|', $this->disponibilite);
            $finalDispoTab = [];

            for($i = 0; $i < sizeof($dispoTab); $i++) {
                $dispoSeparee = explode('-', $dispoTab[$i]);
                $popSeparee = array_pop($dispoSeparee);
                $popSepareeLast = array_pop($dispoSeparee);
                $jourDebut = explode(';', $popSepareeLast);
                $last = array_pop($jourDebut);
                $first = array_pop($jourDebut);
                $dispoFormeFinale = "Le " . $first . " de " . $last . " à " . $popSeparee;
                $finalDispoTab[$i] = $dispoFormeFinale;
            }

            return $finalDispoTab;
        } else {
            return null;
        }
    }

    public function removeChoice($indexChoice): static
    {
        $dispoTab = explode('|', $this->disponibilite);
        unset($dispoTab[$indexChoice]);

        $this->disponibilite = "";
        foreach($dispoTab as $dispo) {
            $this->disponibilite = $this->disponibilite . "" . $dispo . "|";
        }

        return $this;
    }

    public function setDisponibilite(string $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

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

    public function getSizeDatesAnnonce() : int
    {
        return sizeof($this->dates_annonce);
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

    public function setEstRendu(bool $var): static
    {
        $this->est_rendu = $var;

        return $this;
    }

    public function getEstRendu()
    {

        return $this->est_rendu;
    }

    public function setEstEnLitige(bool $var): static
    {
        $this->est_en_litige = $var;

        return $this;
    }

    public function getEstEnLitige()
    {

        return $this->est_en_litige;
    }
}