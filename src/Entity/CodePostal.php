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

    #[ORM\OneToMany(mappedBy: 'codePostal', targetEntity: Adresse::class)]
    private Collection $adresses;



    public function __construct()
    {
        $this->villes = new ArrayCollection();
        $this->adresses = new ArrayCollection();
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

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setCodePostal($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCodePostal() === $this) {
                $adress->setCodePostal(null);
            }
        }

        return $this;
    }
}
