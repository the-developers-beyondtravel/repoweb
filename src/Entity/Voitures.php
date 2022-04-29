<?php

namespace App\Entity;

use App\Repository\VoituresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoituresRepository::class)
 */
class Voitures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="idVoiture", type="integer", nullable=false)
     */
    private $idVoiture;

    /**
     * @ORM\ManyToOne(targetEntity="Societedelocation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSoclocation", referencedColumnName="idSoclocation")
     * })
     */
    private $idSoclocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbsieges;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;


    public function __construct()
    {
        $this->id_voiture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->idVoiture;
    }

    public function getIdSoclocation(): ?societedelocation
    {
        return $this->idSoclocation;
    }

    public function setIdSoclocation(?societedelocation $idSoclocation): self
    {
        $this->idSoclocation = $idSoclocation;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getNbsieges(): ?int
    {
        return $this->nbsieges;
    }

    public function setNbsieges(int $nbsieges): self
    {
        $this->nbsieges = $nbsieges;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

}
