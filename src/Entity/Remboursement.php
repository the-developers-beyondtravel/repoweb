<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RemboursementRepository::class)
 */
class Remboursement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\Positive
     */
    private $prixtotal;

    /**
     * @ORM\ManyToOne(targetEntity=Reclamation::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idreclamation", referencedColumnName="idreclamation")
     * })
     */
    private $reclamation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixtotal(): ?float
    {
        return $this->prixtotal;
    }

    public function setPrixtotal(?float $prixtotal): self
    {
        $this->prixtotal = $prixtotal;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReclamation()
    {
        return $this->reclamation;
    }

    /**
     * @param mixed $reclamation
     */
    public function setReclamation($reclamation): void
    {
        $this->reclamation = $reclamation;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }




}
