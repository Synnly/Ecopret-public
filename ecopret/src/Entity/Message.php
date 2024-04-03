<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[Groups('message')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('message')]
    #[ORM\Column(length: 1024)]
    private ?string $message = null;

    #[Groups('message')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $expeditaire = null;

    #[Groups('message')]
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Conversation $conversation = null;

    #[ORM\Column]
    private ?bool $lu = null;

    #[ORM\Column]
    private ?bool $envoye = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct()
    {
        $this->date = new \DateTime("now"); // Initialisation de la date à chaque nouveau message
        $this->lu = false;
        $this->envoye = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getExpeditaire(): ?Compte
    {
        return $this->expeditaire;
    }

    public function setExpeditaire(?Compte $expeditaire): static
    {
        $this->expeditaire = $expeditaire;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function isLu(): ?bool
    {
        return $this->lu;
    }

    public function setLu(bool $lu): static
    {
        $this->lu = $lu;

        return $this;
    }

    public function isEnvoye(): ?bool
    {
        return $this->envoye;
    }

    public function setEnvoye(bool $envoye): static
    {
        $this->envoye = $envoye;

        return $this;
    }

}
