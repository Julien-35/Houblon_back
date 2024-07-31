<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 250)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Biere::class, mappedBy: 'Categorie')]
    private Collection $bieres;

    public function __construct()
    {
        $this->bieres = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Biere>
     */
    public function getBieres(): Collection
    {
        return $this->bieres;
    }

    public function addBiere(Biere $biere): static
    {
        if (!$this->bieres->contains($biere)) {
            $this->bieres->add($biere);
            $biere->setCategorie($this);
        }

        return $this;
    }

    public function removeBiere(Biere $biere): static
    {
        if ($this->bieres->removeElement($biere)) {
            // set the owning side to null (unless already changed)
            if ($biere->getCategorie() === $this) {
                $biere->setCategorie(null);
            }
        }

        return $this;
    }
}
