<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_offre", type="integer", nullable=false)
     * @Groups("offre")
     * @Groups("posts:read")
     */
    public $id_offre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     * minMessage = "La destination doit composer au mois {{ limit }} caractères",
     * maxMessage = "La destination doit composer au plus {{ limit }} caractères"
     * )
     * @Groups("offre")
     * @Groups("posts:read")
     */
    private $destination;

    /**
     * @var \DateTime
    *
     * @ORM\Column(type="date")
     * @Groups("offre")
     * @Groups("posts:read")
     */
    private $date_debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Groups("offre")
     * @Groups("posts:read")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\Positive
     * @Groups("offre")
     * @Groups("posts:read")
     */
    private $prix;


    public function __construct()
    {
        $this->id_offre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id_offre;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


}
