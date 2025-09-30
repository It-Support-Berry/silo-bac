<?php

namespace App\Entity;

use App\Repository\CodejdeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CodejdeRepository::class)
 *  @UniqueEntity("code", message="Ce code existe déjà")
 */
class Codejde
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity=Matiere::class, inversedBy="codejde", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return (String)$this->code;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get the value of code
     */ 
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */ 
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }
}
