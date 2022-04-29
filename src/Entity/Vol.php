<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VolRepository::class)
 */
class Vol
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_vol", type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Destination;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_aller;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_retour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $classe;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $compagnie_aerienne;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_vol;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="id_vol")
     */
    private $id_vol;

    public function __construct()
    {
        $this->id_vol = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestination(): ?string
    {
        return $this->Destination;
    }

    public function setDestination(string $Destination): self
    {
        $this->Destination = $Destination;

        return $this;
    }

    public function getDateAller(): ?\DateTimeInterface
    {
        return $this->Date_aller;
    }

    public function setDateAller(\DateTimeInterface $Date_aller): self
    {
        $this->Date_aller = $Date_aller;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->Date_retour;
    }

    public function setDateRetour(\DateTimeInterface $Date_retour): self
    {
        $this->Date_retour = $Date_retour;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCompagnieAerienne(): ?string
    {
        return $this->compagnie_aerienne;
    }

    public function setCompagnieAerienne(string $compagnie_aerienne): self
    {
        $this->compagnie_aerienne = $compagnie_aerienne;

        return $this;
    }

    public function getTypeVol(): ?string
    {
        return $this->type_vol;
    }

    public function setTypeVol(string $type_vol): self
    {
        $this->type_vol = $type_vol;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getIdVol(): Collection
    {
        return $this->id_vol;
    }

    public function addIdVol(Reservation $idVol): self
    {
        if (!$this->id_vol->contains($idVol)) {
            $this->id_vol[] = $idVol;
            $idVol->setIdVol($this);
        }

        return $this;
    }

    public function removeIdVol(Reservation $idVol): self
    {
        if ($this->id_vol->removeElement($idVol)) {
            // set the owning side to null (unless already changed)
            if ($idVol->getIdVol() === $this) {
                $idVol->setIdVol(null);
            }
        }

        return $this;
    }
}
