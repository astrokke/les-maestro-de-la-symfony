<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $URL_photo = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'Photos')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getURLPhoto(): ?string
    {
        return $this->URL_photo;
    }

    public function setURLPhoto(string $URL_photo): static
    {
        $this->URL_photo = $URL_photo;

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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
