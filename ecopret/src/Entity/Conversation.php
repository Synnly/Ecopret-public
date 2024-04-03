<?php

namespace App\Entity;

use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
class Conversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Message $dernierMessage = null;

    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $participant1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $participant2 = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
            $this->dernierMessage = $message;
        }

        return $this;
    }

    // On fait acte de présence
    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDernierMessage() === $this) {
                $message->setDernierMessage(null);  // Le dernier message peut pas etre null mais de toute facon le user peut pas supprimer de messages donc osef
            }
        }

        return $this;
    }

    public function getDernierMessage(): ?Message
    {
        return $this->dernierMessage;
    }

    public function setDernierMessage(Message $dernierMessage): static
    {
        $this->dernierMessage = $dernierMessage;

        return $this;
    }

    public function getParticipant1(): ?Compte
    {
        return $this->participant1;
    }

    public function setParticipant1(?Compte $participant1): static
    {
        $this->participant1 = $participant1;

        return $this;
    }

    public function getParticipant2(): ?Compte
    {
        return $this->participant2;
    }

    public function setParticipant2(?Compte $participant2): static
    {
        $this->participant2 = $participant2;

        return $this;
    }

    public function estParticipant(?Compte $participant): bool
    {
        return $this->participant1 === $participant || $this->participant2 === $participant;
    }
}