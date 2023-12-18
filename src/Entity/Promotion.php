<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
class Promotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $Taux_promotion = null;

    #[ORM\Column(length: 255)]
    private ?string $code_promotion = null;

    #[ORM\OneToMany(mappedBy: 'promotion', targetEntity: Produit::class)]
    private Collection $Produit;

    public function __construct()
    {
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

    public function getTauxPromotion(): ?float
    {
        return $this->Taux_promotion;
    }

    public function setTauxPromotion(float $Taux_promotion): static
    {
        $this->Taux_promotion = $Taux_promotion;

        return $this;
    }

    public function getCodePromotion(): ?string
    {
        return $this->code_promotion;
    }

    public function setCodePromotion(string $code_promotion): static
    {
        $this->code_promotion = $code_promotion;

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
            $produit->setPromotion($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->Produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getPromotion() === $this) {
                $produit->setPromotion(null);
            }
        }

        return $this;
    }
}
