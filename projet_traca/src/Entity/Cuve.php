<?php

namespace App\Entity;

use App\Repository\CuveRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CuveRepository::class)
 *  @UniqueEntity("numero", message="Cette cuve existe déjà")
 * 
 */
class Cuve
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
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Silo::class, inversedBy="cuves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $silo;

    public function __toString()
    {
        return (String)$this->numero;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSilo(): ?Silo
    {
        return $this->silo;
    }

    public function setSilo(?Silo $silo): self
    {
        $this->silo = $silo;

        return $this;
    }
}
