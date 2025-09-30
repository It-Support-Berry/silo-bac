<?php

namespace App\Entity;

use App\Repository\MatiereBacRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereBacRepository::class)
 */
class MatiereBac
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
     * @ORM\OneToMany(targetEntity=CodejdeBac::class, mappedBy="matiereSac")
     */
    private $codejdeBac;

    public function __construct()
    {
        $this->codejdeBac = new ArrayCollection();
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
     * @return Collection<int, CodejdeBac>
     */
    public function getCodejdeBac(): Collection
    {
        return $this->codejdeBac;
    }

    public function addCodeJDESac(CodejdeBac $codejdeBac): self
    {
        if (!$this->codejdeBac->contains($codejdeBac)) {
            $this->codejdeBac[] = $codejdeBac;
            $codejdeBac->setMatiere($this);
        }

        return $this;
    }

    public function removeCodeJDESac(CodejdeBac $codeJDESac): self
    {
        if ($this->codejdeBac->removeElement($codeJDESac)) {
            // set the owning side to null (unless already changed)
            if ($codeJDESac->getMatiere() === $this) {
                $codeJDESac->setMatiere(null);
            }
        }

        return $this;
    }


}
