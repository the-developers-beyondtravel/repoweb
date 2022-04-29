<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_reservation", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $id_user;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_offre", referencedColumnName="id_offre")
     * })
     */
    private $id_offre;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Vol")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_vol", referencedColumnName="id_vol")
     * })
     */
    private $id_vol;

    /**
     * @ORM\ManyToOne(targetEntity="Guidetouristique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_guidetouristique", referencedColumnName="id_guide")
     * })
     */
    private $id_guidetouristique;

    /**
     * @ORM\ManyToOne(targetEntity="Voitures")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_voiture", referencedColumnName="idVoiture")
     * })
     */
    private $id_voiture;

    /**
     * @ORM\ManyToOne(targetEntity="Hotels")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_hotel", referencedColumnName="idHotels")
     * })
     */
    private $id_hotel;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user): void
    {
        $this->id_user = $id_user;
    }


    /**
     * @return mixed
     */
    public function getIdOffre()
    {
        return $this->id_offre;
    }

    /**
     * @param mixed $id_offre
     */
    public function setIdOffre($id_offre): void
    {
        $this->id_offre = $id_offre;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }



    public function getIdVol(): ?vol
    {
        return $this->id_vol;
    }

    public function setIdVol(?vol $id_vol): self
    {
        $this->id_vol = $id_vol;

        return $this;
    }

    public function getIdGuidetouristique(): ?guidetouristique
    {
        return $this->id_guidetouristique;
    }

    public function setIdGuidetouristique(?guidetouristique $id_guidetouristique): self
    {
        $this->id_guidetouristique = $id_guidetouristique;

        return $this;
    }

    public function getIdVoiture(): ?voitures
    {
        return $this->id_voiture;
    }

    public function setIdVoiture(?voitures $id_voiture): self
    {
        $this->id_voiture = $id_voiture;

        return $this;
    }

    public function getIdHotel(): ?hotels
    {
        return $this->id_hotel;
    }

    public function setIdHotel(?hotels $id_hotel): self
    {
        $this->id_hotel = $id_hotel;

        return $this;
    }
}
