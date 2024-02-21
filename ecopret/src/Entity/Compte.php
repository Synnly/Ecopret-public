<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomCompte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PrenomCompte = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasseCompte = null;

    #[ORM\Column(length: 255)]
    private ?string $AdresseMailCOmpte = null;

    #[ORM\ManyToMany(targetEntity: Notification::class, mappedBy: 'no_compte')]
    private Collection $notifications;

    #[ORM\ManyToMany(targetEntity: Lieu::class)]
    private Collection $lieu;

    #[ORM\ManyToMany(targetEntity: Note::class)]
    private Collection $notes;

    #[ORM\ManyToMany(targetEntity: Transaction::class, inversedBy: 'comptes')]
    private Collection $transactions;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?CarteCredit $carte_credit = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $resetToken = null;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->lieu = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
        
    } 

    public function getUserIdentifier(): string
    {
        return $this->AdresseMailCOmpte;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCompte(): ?string
    {
        return $this->NomCompte;
    }

    public function setNomCompte(string $NomCompte): static
    {
        $this->NomCompte = $NomCompte;

        return $this;
    }

    public function getPrenomCompte(): ?string
    {
        return $this->PrenomCompte;
    }

    public function setPrenomCompte(?string $PrenomCompte): static
    {
        $this->PrenomCompte = $PrenomCompte;

        return $this;
    }

    public function getMotDePasseCompte(): ?string
    {
        return $this->motDePasseCompte;
    }

    public function setMotDePasseCompte(string $motDePasseCompte): static
    {
        $this->motDePasseCompte = $motDePasseCompte;

        return $this;
    }

    public function getAdresseMailCOmpte(): ?string
    {
        return $this->AdresseMailCOmpte;
    }

    public function setAdresseMailCOmpte(string $AdresseMailCOmpte): static
    {
        $this->AdresseMailCOmpte = $AdresseMailCOmpte;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->addNoCompte($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            $notification->removeNoCompte($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieu(): Collection
    {
        return $this->lieu;
    }

    public function addLieu(Lieu $lieu): static
    {
        if (!$this->lieu->contains($lieu)) {
            $this->lieu->add($lieu);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): static
    {
        $this->lieu->removeElement($lieu);

        return $this;
    }

    /**
     * @return Collection<int, note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
        }

        return $this;
    }

    public function removeNote(note $note): static
    {
        $this->notes->removeElement($note);

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        $this->transactions->removeElement($transaction);

        return $this;
    }

    public function getCarteCredit(): ?CarteCredit
    {
        return $this->carte_credit;
    }

    public function setCarteCredit(?CarteCredit $carte_credit): static
    {
        $this->carte_credit = $carte_credit;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->motDePasseCompte;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
