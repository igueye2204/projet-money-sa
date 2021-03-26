<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Compte;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\DepotRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=DepotRepository::class)
 * @ApiResource(
 * collectionOperations = {
 *      "get"={
 *          "path"   = "/admin/depots",
 *          "method" = "get"
 *     },
 *     "add_depot" = {
 *          "method" = "post",
 *      "deserialize" = false,
 *      "security" = "(is_granted('ROLE_ADMIN') or is_granted('ROLE_CAISSIER'))",
 *      "security_message" = "Accès refusé!"
 *      }
 *  },
 *  itemOperations = {
 *      "cancel_warehouse" = {
 *          "method" = "delete",
 *      "deserialize" = false, 
 *  },
 *     "get",
 *  })
 */
class Depot
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantDepot;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="depots")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="depots")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontantDepot(): ?int
    {
        return $this->montantDepot;
    }

    public function setMontantDepot(int $montantDepot): self
    {
        $this->montantDepot = $montantDepot;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
