<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_voie = null;

    #[ORM\Column(length: 255)]
    private ?string $rue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement = null;

    #[ORM\OneToMany(mappedBy: 'est_livré', targetEntity: Commande::class)]
    private Collection $est_livre;

    #[ORM\OneToMany(mappedBy: 'est_facture', targetEntity: Commande::class)]
    private Collection $est_facture;

    #[ORM\ManyToOne(inversedBy: 'adresse')]
    private ?Ville $Ville = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    


    public function __construct()
    {
        $this->est_livre = new ArrayCollection();
        $this->est_facture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumVoie(): ?int
    {
        return $this->num_voie;
    }

    public function setNumVoie(int $num_voie): static
    {
        $this->num_voie = $num_voie;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): static
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getEstLivre(): Collection
    {
        return $this->est_livre;
    }

    public function addEstLivre(Commande $estLivre): static
    {
        if (!$this->est_livre->contains($estLivre)) {
            $this->est_livre->add($estLivre);
            $estLivre->setEstLivre($this);
        }

        return $this;
    }

    public function removeEstLivre(Commande $estLivre): static
    {
        if ($this->est_livre->removeElement($estLivre)) {
            // set the owning side to null (unless already changed)
            if ($estLivre->getEstLivre() === $this) {
                $estLivre->setEstLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getEstFacture(): Collection
    {
        return $this->est_facture;
    }

    public function addEstFacture(Commande $estFacture): static
    {
        if (!$this->est_facture->contains($estFacture)) {
            $this->est_facture->add($estFacture);
            $estFacture->setEstFacture($this);
        }

        return $this;
    }

    public function removeEstFacture(Commande $estFacture): static
    {
        if ($this->est_facture->removeElement($estFacture)) {
            // set the owning side to null (unless already changed)
            if ($estFacture->getEstFacture() === $this) {
                $estFacture->setEstFacture(null);
            }
        }

        return $this;
    }



   

    public function getVille(): ?Ville
    {
        return $this->Ville;
    }

    public function setVille(?Ville $Ville): static
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

   
}
