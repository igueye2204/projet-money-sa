<?php

namespace App\Entity;

use App\Repository\TableauDesFraisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauDesFraisRepository::class)
 */
class TableauDesFrais
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantMin;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantMax;

    /**
     * @ORM\Column(type="float")
     */
    private $frais;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantMin(): ?int
    {
        return $this->montantMin;
    }

    public function setMontantMin(int $montantMin): self
    {
        $this->montantMin = $montantMin;

        return $this;
    }

    public function getMontantMax(): ?int
    {
        return $this->montantMax;
    }

    public function setMontantMax(int $montantMax): self
    {
        $this->montantMax = $montantMax;

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
}
