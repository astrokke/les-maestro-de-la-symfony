<?php

namespace App\Entity;

use App\Repository\PanierProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierProduitRepository::class)]
class PanierProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'panierProduits')]
    private ?Produit $Produit = null;

    #[ORM\ManyToOne(inversedBy: 'panierProduits')]
    private ?Panier $Panier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->Produit;
    }

    public function setProduit(?Produit $Produit): static
    {
        $this->Produit = $Produit;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->Panier;
    }

    public function setPanier(?Panier $Panier): static
    {
        $this->Panier = $Panier;

        return $this;
    }
}
