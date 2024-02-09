<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message_notification = null;

    #[ORM\ManyToMany(targetEntity: compte::class, inversedBy: 'notifications')]
    private Collection $no_compte;

    public function __construct()
    {
        $this->no_compte = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessageNotification(): ?string
    {
        return $this->message_notification;
    }

    public function setMessageNotification(string $message_notification): static
    {
        $this->message_notification = $message_notification;

        return $this;
    }

    /**
     * @return Collection<int, compte>
     */
    public function getNoCompte(): Collection
    {
        return $this->no_compte;
    }

    public function addNoCompte(compte $noCompte): static
    {
        if (!$this->no_compte->contains($noCompte)) {
            $this->no_compte->add($noCompte);
        }

        return $this;
    }

    public function removeNoCompte(compte $noCompte): static
    {
        $this->no_compte->removeElement($noCompte);

        return $this;
    }
}
