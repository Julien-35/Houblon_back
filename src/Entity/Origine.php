<?php

// src/Entity/Origine.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrigineRepository::class)]
class Origine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['origine:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['origine:read'])]
    private ?string $label = null;

    #[ORM\OneToMany(targetEntity: Biere::class, mappedBy: 'origine')]
    #[Groups(['origine:read'])]
    private Collection $bieres;

    public function __construct()
    {
        $this->bieres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return Collection<int, Biere>
     */
    public function getBieres(): Collection
    {
        return $this->bieres;
    }

    public function addBiere(Biere $biere): self
    {
        if (!$this->bieres->contains($biere)) {
            $this->bieres->add($biere);
            $biere->setOrigine($this);
        }
        return $this;
    }

    public function removeBiere(Biere $biere): self
    {
        if ($this->bieres->removeElement($biere)) {
            // set the owning side to null (unless already changed)
            if ($biere->getOrigine() === $this) {
                $biere->setOrigine(null);
            }
        }
        return $this;
    }
}
