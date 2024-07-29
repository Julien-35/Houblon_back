<?php

namespace App\Entity;

use App\Repository\BiereRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BiereRepository::class)]
class Biere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['biere:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['biere:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 250)]
    #[Groups(['biere:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 10)]
    #[Groups(['biere:read'])]
    private ?string $taux_alcool = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['biere:read'])]
    private ?string $image_data = null;

    #[ORM\ManyToOne(targetEntity: Origine::class, inversedBy: 'bieres')]
    #[Groups(['biere:read'])]
    private ?Origine $origine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTauxAlcool(): ?string
    {
        return $this->taux_alcool;
    }

    public function setTauxAlcool(string $taux_alcool): static
    {
        $this->taux_alcool = $taux_alcool;

        return $this;
    }

    public function getImageData(): ?string
    {
        return $this->image_data;
    }

    public function setImageData(string $image_data): static
    {
        $this->image_data = $image_data;

        return $this;
    }

    public function getOrigine(): ?Origine
    {
        return $this->origine;
    }

    public function setOrigine(?Origine $origine): static
    {
        $this->origine = $origine;

        return $this;
    }
}
