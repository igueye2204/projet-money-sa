<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InfotransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"info:read"}},
 * collectionOperations = {
 *
 *     "get" = {
 *           "path" = "admin/infotransactions",
 *           "method" = "get",
 *          "deserialize" = false
 *     },
 *      "post" = {
 *          "path" = "admin/infotransactions",
 *          "method" = "post",
 *          "deserialize" = false
 *      },
 *     "show_transaction" = {
 *           "method" = "get",
 *          "deserialize" = false
 *     }
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "admin/infotransaction/{id}",
 *      }
 *   }
 * )
 * @ORM\Entity(repositoryClass=InfotransactionRepository::class)
 */
class Infotransaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"info:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"info:read"})
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"info:read"})
     */
    private $compte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"info:read"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"info:read"})
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"info:read"})
     */
    private $frais;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"info:read"})
     */
    private $codeTransaction;

    /**
     * @ORM\Column(type="date")
     * @Groups({"info:read"})
     */
    private $dateTransaction;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"info:read"})
     */
    private $PrenomClient;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"info:read"})
     */
    private $nomclient;

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

    public function getCompte(): ?int
    {
        return $this->compte;
    }

    public function setCompte(int $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

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

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->dateTransaction;
    }

    public function setDateTransaction(\DateTimeInterface $dateTransaction): self
    {
        $this->dateTransaction = $dateTransaction;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->PrenomClient;
    }

    public function setPrenomClient(string $PrenomClient): self
    {
        $this->PrenomClient = $PrenomClient;

        return $this;
    }

    public function getNomclient(): ?string
    {
        return $this->nomclient;
    }

    public function setNomclient(string $nomclient): self
    {
        $this->nomclient = $nomclient;

        return $this;
    }
}
