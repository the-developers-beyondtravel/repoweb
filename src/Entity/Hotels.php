<?php

namespace App\Entity;

use App\Repository\HotelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HotelsRepository::class)
 */
class Hotels
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="idHotels", type="integer", nullable=false)
     */
    private $idHotels;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbetoiles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pointfort;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;


    public function __construct()
    {
        $this->id_hotel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->idHotels;
    }

    public function getNbetoiles(): ?int
    {
        return $this->nbetoiles;
    }

    public function setNbetoiles(int $nbetoiles): self
    {
        $this->nbetoiles = $nbetoiles;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPointfort(): ?string
    {
        return $this->pointfort;
    }

    public function setPointfort(string $pointfort): self
    {
        $this->pointfort = $pointfort;

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
