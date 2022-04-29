<?php

namespace App\Entity;

use App\Repository\SocietedelocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocietedelocationRepository::class)
 */
class Societedelocation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="idSoclocation", type="integer", nullable=false)
     */
    private $idSoclocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomdesoc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;


    public function __construct()
    {
        $this->idSoclocation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->idSoclocation;
    }

    public function getNomdesoc(): ?string
    {
        return $this->nomdesoc;
    }

    public function setNomdesoc(string $nomdesoc): self
    {
        $this->nomdesoc = $nomdesoc;

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

}
