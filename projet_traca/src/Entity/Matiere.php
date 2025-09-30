<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 * @UniqueEntity("nom", message="Cette matière existe déjà")
 */
class Matiere
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
     * @ORM\OneToOne(targetEntity=Codejde::class, mappedBy="matiere", cascade={"persist", "remove"})
     */
    private $codejde;


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

    public function getCodejde(): ?Codejde
    {
        return $this->codejde;
    }

    public function setCodejde(Codejde $codejde): self
    {
        // set the owning side of the relation if necessary
        if ($codejde->getMatiere() !== $this) {
            $codejde->setMatiere($this);
        }

        $this->codejde = $codejde;

        return $this;
    }


   
}
