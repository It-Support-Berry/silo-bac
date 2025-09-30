<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AtelierRepository::class)
 * @UniqueEntity("nom", message="Ce nom existe déjà")
 */
class Atelier
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
     * @ORM\OneToMany(targetEntity=Silo::class, mappedBy="atelier")
     */
    private $silo;

    public function __construct()
    {
        $this->silo = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Silo>
     */
    public function getSilo(): Collection
    {
        return $this->silo;
    }

    public function addSilo(Silo $silo): self
    {
        if (!$this->silo->contains($silo)) {
            $this->silo[] = $silo;
            $silo->setAtelier($this);
        }

        return $this;
    }

    public function removeSilo(Silo $silo): self
    {
        if ($this->silo->removeElement($silo)) {
            // set the owning side to null (unless already changed)
            if ($silo->getAtelier() === $this) {
                $silo->setAtelier(null);
            }
        }

        return $this;
    }
}
