<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255)]
    private ?string $nom = null;



    #[ORM\ManyToOne(inversedBy: 'Ville')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Departement $Departement = null;

    #[ORM\OneToMany(mappedBy: 'Ville', targetEntity: Adresse::class)]
    private Collection $adresse;

    #[ORM\ManyToMany(targetEntity: CodePostal::class, inversedBy: 'villes')]
    private Collection $Code_postal;

    public function __construct()
    {
        $this->adresse = new ArrayCollection();
        $this->Code_postal = new ArrayCollection();
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

    /**
     * @return Collection<int, Adresse>
     */


    public function getDepartement(): ?Departement
    {
        return $this->Departement;
    }

    public function setDepartement(?Departement $Departement): static
    {
        $this->Departement = $Departement;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresse(): Collection
    {
        return $this->adresse;
    }

    public function addAdresse(Adresse $adresse): static
    {
        if (!$this->adresse->contains($adresse)) {
            $this->adresse->add($adresse);
            $adresse->setVille($this);
        }

        return $this;
    }

    public function removeAdresse(Adresse $adresse): static
    {
        if ($this->adresse->removeElement($adresse)) {
            // set the owning side to null (unless already changed)
            if ($adresse->getVille() === $this) {
                $adresse->setVille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CodePostal>
     */
    public function getCodePostal(): Collection
    {
        return $this->Code_postal;
    }

    public function addCodePostal(CodePostal $codePostal): static
    {
        if (!$this->Code_postal->contains($codePostal)) {
            $this->Code_postal->add($codePostal);
        }

        return $this;
    }

    public function removeCodePostal(CodePostal $codePostal): static
    {
        $this->Code_postal->removeElement($codePostal);

        return $this;
    }
}
