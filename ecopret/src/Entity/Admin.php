<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Litige::class)]
    private Collection $litiges;

    public function __construct()
    {
        $this->litiges = new ArrayCollection();
    }

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
            $litige->setAdmin($this);
        }

        return $this;
    }

    public function removeLitige(Litige $litige): static
    {
        if ($this->litiges->removeElement($litige)) {
            // set the owning side to null (unless already changed)
            if ($litige->getAdmin() === $this) {
                $litige->setAdmin(null);
            }
        }

        return $this;
    }
}
