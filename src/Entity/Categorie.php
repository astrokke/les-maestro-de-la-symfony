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

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categorie_enfant')]
    private ?self $categorie_parente = null;

    #[ORM\OneToMany(mappedBy: 'categorie_parente', targetEntity: self::class)]
    private Collection $categorie_enfant;

    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'categories')]
    private Collection $Produit;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Photos::class)]
    private Collection $Photos;

    public function __construct()
    {
        $this->categorie_enfant = new ArrayCollection();
        $this->Produit = new ArrayCollection();
        $this->Photos = new ArrayCollection();
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

    public function getCategorieParente(): ?self
    {
        return $this->categorie_parente;
    }

    public function setCategorieParente(?self $categorie_parente): static
    {
        $this->categorie_parente = $categorie_parente;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategorieEnfant(): Collection
    {
        return $this->categorie_enfant;
    }

    public function addCategorieEnfant(self $categorieEnfant): static
    {
        if (!$this->categorie_enfant->contains($categorieEnfant)) {
            $this->categorie_enfant->add($categorieEnfant);
            $categorieEnfant->setCategorieParente($this);
        }

        return $this;
    }

    public function removeCategorieEnfant(self $categorieEnfant): static
    {
        if ($this->categorie_enfant->removeElement($categorieEnfant)) {
            // set the owning side to null (unless already changed)
            if ($categorieEnfant->getCategorieParente() === $this) {
                $categorieEnfant->setCategorieParente(null);
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

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->Photos;
    }

    public function addPhoto(Photos $photo): static
    {
        if (!$this->Photos->contains($photo)) {
            $this->Photos->add($photo);
            $photo->setCategorie($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->Photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCategorie() === $this) {
                $photo->setCategorie(null);
            }
        }

        return $this;
    }
}
