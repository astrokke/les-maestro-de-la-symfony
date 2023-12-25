<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_departement = null;

    #[ORM\OneToMany(mappedBy: 'Departement', targetEntity: Ville::class)]
    private Collection $Ville;

    #[ORM\ManyToOne(inversedBy: 'Departement')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    public function __construct()
    {
        $this->Ville = new ArrayCollection();
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

    public function getNumeroDepartement(): ?string
    {
        return $this->numero_departement;
    }

    public function setNumeroDepartement(int $numero_departement): static
    {
        $this->numero_departement = $numero_departement;

        return $this;
    }

    /**
     * @return Collection<int, Ville>
     */
    public function getVille(): Collection
    {
        return $this->Ville;
    }

    public function addVille(Ville $ville): static
    {
        if (!$this->Ville->contains($ville)) {
            $this->Ville->add($ville);
            $ville->setDepartement($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): static
    {
        if ($this->Ville->removeElement($ville)) {
            // set the owning side to null (unless already changed)
            if ($ville->getDepartement() === $this) {
                $ville->setDepartement(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }
}
