<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"agence:read"}},
 * 
 *  collectionOperations = {
 *
 *      "get_agence" = {  
 *            "method" = "get",
 *          "deserialize" = false
 *     },
 *     "create_agence" = {
 *            "method" = "post",
 *          "deserialize" = false
 *     },
 *  },
 *  itemOperations = {
 *    
 *     "get",
 *     "bloque_agence" = {  
 *            "method" = "delete",
 *          "deserialize" = false
 *     }
 *  }
 * )
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read", "transaction:read"})
     */
    private $nomAgence;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read"})
     */
    private $addressAgence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status=0;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence")
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAgence(): ?string
    {
        return $this->nomAgence;
    }

    public function setNomAgence(string $nomAgence): self
    {
        $this->nomAgence = $nomAgence;

        return $this;
    }

    public function getAddressAgence(): ?string
    {
        return $this->addressAgence;
    }

    public function setAddressAgence(string $addressAgence): self
    {
        $this->addressAgence = $addressAgence;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }
}
