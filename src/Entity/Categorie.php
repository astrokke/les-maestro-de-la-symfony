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

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categorie_parente')]
    private ?self $categorie_enfant = null;

    #[ORM\OneToMany(mappedBy: 'categorie_enfant', targetEntity: self::class)]
    private Collection $categorie_parente;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'categories')]
    private Collection $Produit;

    public function __construct()
    {
        $this->categorie_parente = new ArrayCollection();
        $this->Produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorieEnfant(): ?self
    {
        return $this->categorie_enfant;
    }

    public function setCategorieEnfant(?self $categorie_enfant): static
    {
        $this->categorie_enfant = $categorie_enfant;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategorieParente(): Collection
    {
        return $this->categorie_parente;
    }

    public function addCategorieParente(self $categorieParente): static
    {
        if (!$this->categorie_parente->contains($categorieParente)) {
            $this->categorie_parente->add($categorieParente);
            $categorieParente->setCategorieEnfant($this);
        }

        return $this;
    }

    public function removeCategorieParente(self $categorieParente): static
    {
        if ($this->categorie_parente->removeElement($categorieParente)) {
            // set the owning side to null (unless already changed)
            if ($categorieParente->getCategorieEnfant() === $this) {
                $categorieParente->setCategorieEnfant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->Produit;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->Produit->contains($produit)) {
            $this->Produit->add($produit);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        $this->Produit->removeElement($produit);

        return $this;
    }
}
