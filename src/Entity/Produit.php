<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix_ht = null;



    #[ORM\ManyToOne(inversedBy: 'Produit')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TVA $TVA = null;


    #[ORM\ManyToOne(inversedBy: 'Produit')]
    private ?Promotion $promotion = null;


    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Photos::class, orphanRemoval:true, cascade:["persist"])]
    private Collection $Photos;

    #[ORM\OneToMany(mappedBy: 'Produit', targetEntity: PanierProduit::class)]
    private Collection $panierProduits;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

    public function __construct()
    {

        $this->Photos = new ArrayCollection();
        $this->panierProduits = new ArrayCollection();
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

    /*public function __toString()
    {
        return $this->nom . ' ' . $this->prenom;
    }
    */

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixHt(): ?float
    {
        return $this->prix_ht;
    }

    public function setPrixHt(float $prix_ht): static
    {
        $this->prix_ht = $prix_ht;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */


    public function getTVA(): ?TVA
    {
        return $this->TVA;
    }

    public function setTVA(?TVA $TVA): static
    {
        $this->TVA = $TVA;

        return $this;
    }

    /**
     * @return Collection<int, Photos>
     */


    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): static
    {
        $this->promotion = $promotion;

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
            $photo->setProduit($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): static
    {
        if ($this->Photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProduit() === $this) {
                $photo->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PanierProduit>
     */
    public function getPanierProduits(): Collection
    {
        return $this->panierProduits;
    }

    public function addPanierProduit(PanierProduit $panierProduit): static
    {
        if (!$this->panierProduits->contains($panierProduit)) {
            $this->panierProduits->add($panierProduit);
            $panierProduit->setProduit($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): static
    {
        if ($this->panierProduits->removeElement($panierProduit)) {
            // set the owning side to null (unless already changed)
            if ($panierProduit->getProduit() === $this) {
                $panierProduit->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}
