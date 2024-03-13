<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Annonce $id_annonce = null;

    #[ORM\Column(length: 16380, nullable:true)]
    private ?string $dates_sevice = null;

    #[ORM\Column(nullable:true)]
    private ?int $id_client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAnnonce(): ?Annonce
    {
        return $this->id_annonce;
    }

    public function getIdClient(): ?int
    {
        return $this->id_client;
    }

    public function getDatesService(): ?string
    {
        return $this->dates_service;
    }

    public function setIdAnnonce(Annonce $id_annonce): static
    {
        $this->id_annonce = $id_annonce;

        return $this;
    }

    public function setDatesService(string $dates_service): static
    {
        $this->dates_service = $dates_service;

        return $this;
    }
    
    public function setIdClient(int $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }
}
