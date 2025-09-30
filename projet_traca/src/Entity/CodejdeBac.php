<?php

namespace App\Entity;

use App\Repository\CodejdeBacRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CodejdeBacRepository::class)
 */
class CodejdeBac
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=MatiereBac::class, inversedBy="CodejdeBac")
     */
    private $matiere;


    
    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
        return (String)$this->nom;
    }

    public function getMatiere(): ?MatiereBac
    {
        return $this->matiere;
    }

    public function setMatiere(?MatiereBac $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }


}
