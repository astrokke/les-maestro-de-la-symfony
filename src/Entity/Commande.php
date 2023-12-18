<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date_commande = null;

    #[ORM\Column]
    private ?float $prix_ttc_commande = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneDeCommande::class)]
    private Collection $ligneDeCommandes;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Livraison $Livraison = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Paiement $Paiement = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Etat $Etat = null;

    #[ORM\ManyToOne(inversedBy: 'est_livre')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $est_livré = null;

    #[ORM\ManyToOne(inversedBy: 'est_facture')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $est_facture = null;

    #[ORM\ManyToOne(inversedBy: 'Commande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\OneToOne(inversedBy: 'commande', cascade: ['persist', 'remove'])]
    private ?Panier $Panier = null;



    public function __construct()
    {
        $this->ligneDeCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeImmutable
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeImmutable $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getPrixTtcCommande(): ?float
    {
        return $this->prix_ttc_commande;
    }

    public function setPrixTtcCommande(float $prix_ttc_commande): static
    {
        $this->prix_ttc_commande = $prix_ttc_commande;

        return $this;
    }

    /**
     * @return Collection<int, LigneDeCommande>
     */
    public function getLigneDeCommandes(): Collection
    {
        return $this->ligneDeCommandes;
    }

    public function addLigneDeCommande(LigneDeCommande $ligneDeCommande): static
    {
        if (!$this->ligneDeCommandes->contains($ligneDeCommande)) {
            $this->ligneDeCommandes->add($ligneDeCommande);
            $ligneDeCommande->setCommande($this);
        }

        return $this;
    }

    public function removeLigneDeCommande(LigneDeCommande $ligneDeCommande): static
    {
        if ($this->ligneDeCommandes->removeElement($ligneDeCommande)) {
            // set the owning side to null (unless already changed)
            if ($ligneDeCommande->getCommande() === $this) {
                $ligneDeCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->Livraison;
    }

    public function setLivraison(?Livraison $Livraison): static
    {
        $this->Livraison = $Livraison;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->Paiement;
    }

    public function setPaiement(?Paiement $Paiement): static
    {
        $this->Paiement = $Paiement;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->Etat;
    }

    public function setEtat(?Etat $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getEstLivré(): ?Adresse
    {
        return $this->est_livré;
    }

    public function setEstLivré(?Adresse $est_livré): static
    {
        $this->est_livré = $est_livré;

        return $this;
    }

    public function getEstFacture(): ?Adresse
    {
        return $this->est_facture;
    }

    public function setEstFacture(?Adresse $est_facture): static
    {
        $this->est_facture = $est_facture;

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
