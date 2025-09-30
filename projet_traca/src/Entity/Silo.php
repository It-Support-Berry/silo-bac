<?php

namespace App\Entity;

use App\Repository\SiloRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SiloRepository::class)
 * @UniqueEntity("numero", message="Le Silo existe déjà")
 */
class Silo
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
     * @ORM\OneToMany(targetEntity=Cuve::class, mappedBy="idsilo")
     */
    private $cuves;

    /**
     * @ORM\ManyToOne(targetEntity=Atelier::class, inversedBy="silo")
     */
    private $atelier;

   

    public function __construct()
    {
        $this->cuves = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Cuve>
     */
    public function getCuves(): Collection
    {
        return $this->cuves;
    }

    public function addCufe(Cuve $cufe): self
    {
        if (!$this->cuves->contains($cufe)) {
            $this->cuves[] = $cufe;
            $cufe->setSilo($this);
        }

        return $this;
    }

    public function removeCufe(Cuve $cufe): self
    {
        if ($this->cuves->removeElement($cufe)) {
            // set the owning side to null (unless already changed)
            if ($cufe->getSilo() === $this) {
                $cufe->setSilo(null);
            }
        }

        return $this;
    }

    public function getAtelier(): ?Atelier
    {
        return $this->atelier;
    }

    public function setAtelier(?Atelier $atelier): self
    {
        $this->atelier = $atelier;

        return $this;
    }
}
