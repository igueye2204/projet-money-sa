<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Compte;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;


/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"transaction:read"}},
 *
 *  collectionOperations = {
 * 
 *       "make_shipment" = {
 *          "path" = "useragence/transaction/send",
 *       "method" = "post",
 * "security" = "(is_granted('ROLE_USERAGENCE') or is_granted('ROLE_ADMINAGENCE'))",
 *       "security_message" = "Accès refusé!"
 * },
 *  "cancel_transaction" = {
 *          "method" = "post",
 *      "deserialize" = false
 *  },
 *     "calculator" = {
 *          "method" = "post",
 *     "deserialize" = false
 *     }
 * 
 *  },
 *  itemOperations = {
 *
 *   "make_withdrawal" = {
 * 
 *   "path" = "useragence/transaction/collection/{id}",
 *  "security" = "(is_granted('ROLE_USERAGENCE') or is_granted('ROLE_ADMINAGENCE'))",
 *   "security_message" = "Accès refusé!"   
 * },
 *  "show_parts" = {
 * 
 *      "method" = "get",
 *      "deserialize" = false
 *  }
 * }
 * )
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "part:read", "getpart"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read"})
     */
    private $montant;

    /**
     * @ORM\Column(type="date")
     * @Groups({"transaction:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date",nullable=true)
     * @Groups({"transaction:read"})
     * @Groups({"transaction:read","part:read"})
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read"})
     */
    private $TTC;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "part:read"})
     */
    private $fraisEtat;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read"})
     */
    private $fraisSystem;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","getpart"})
     */
    private $fraisEnvoie;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups({"transaction:read","part:read"})
     */
    private $fraisretrait;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @Groups({"transaction:read"})
     */
    private $codeTransaction;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transaction")
     * @Groups({"transaction:read"})
     * @ApiSubresource
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @Groups({"transaction:read"})
     */
    private $retrait;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @Groups({"transaction:read"})
     */
    private $deposer;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions",cascade={"persist"})
     * @Groups({"transaction:read", "transaction_do:read"})
     */
    private $recuperer;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions")
     * @Groups({"transaction:read", "transaction_do:read"})
     */
    private $envoyer;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"transaction:read"})
     */
    private $archive=0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    public function getTTC(): ?int
    {
        return $this->TTC;
    }

    public function setTTC(int $TTC): self
    {
        $this->TTC = $TTC;

        return $this;
    }

    public function getFraisEtat(): ?int
    {
        return $this->fraisEtat;
    }

    public function setFraisEtat(int $fraisEtat): self
    {
        $this->fraisEtat = $fraisEtat;

        return $this;
    }

    public function getFraisSystem(): ?int
    {
        return $this->fraisSystem;
    }

    public function setFraisSystem(int $fraisSystem): self
    {
        $this->fraisSystem = $fraisSystem;

        return $this;
    }

    public function getFraisEnvoie(): ?int
    {
        return $this->fraisEnvoie;
    }

    public function setFraisEnvoie(int $fraisEnvoie): self
    {
        $this->fraisEnvoie = $fraisEnvoie;

        return $this;
    }

    public function getFraisretrait(): ?int
    {
        return $this->fraisretrait;
    }

    public function setFraisretrait(int $fraisretrait): self
    {
        $this->fraisretrait = $fraisretrait;

        return $this;
    }

    public function getCodeTransaction(): ?int
    {
        return $this->codeTransaction;
    }

    public function setCodeTransaction(int $codeTransaction): self
    {
        $this->codeTransaction = $codeTransaction;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getRetrait(): ?User
    {
        return $this->retrait;
    }

    public function setRetrait(?User $retrait): self
    {
        $this->retrait = $retrait;

        return $this;
    }

    public function getDeposer(): ?User
    {
        return $this->deposer;
    }

    public function setDeposer(?User $deposer): self
    {
        $this->deposer = $deposer;

        return $this;
    }

    public function getRecuperer(): ?Client
    {
        return $this->recuperer;
    }

    public function setRecuperer(?Client $recuperer): self
    {
        $this->recuperer = $recuperer;

        return $this;
    }

    public function getEnvoyer(): ?Client
    {
        return $this->envoyer;
    }

    public function setEnvoyer(?Client $envoyer): self
    {
        $this->envoyer = $envoyer;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }
}
