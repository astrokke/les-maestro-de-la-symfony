<?php

namespace App\Entity;

use App\Repository\CodePostalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodePostalRepository::class)]
class CodePostal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\ManyToMany(targetEntity: Ville::class, mappedBy: 'Code_postal')]
    private Collection $villes;

    #[ORM\Column(length: 10)]
    private ?string $libelle = null;



    public function __construct()
    {
        $this->villes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ville>
     */

    /**
     * @return Collection<int, Ville>
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): static
    {
        if (!$this->villes->contains($ville)) {
            $this->villes->add($ville);
            $ville->addCodePostal($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): static
    {
        if ($this->villes->removeElement($ville)) {
            $ville->removeCodePostal($this);
        }

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
