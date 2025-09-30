<?php

namespace App\Entity;

use App\Repository\ControleRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass=ControleRepository::class)
 * @ORM\Table(name="controle", indexes={@ORM\Index(columns={"codejde", "matiere", "lot"}, flags={"fulltext"})})
 */
class Controle
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $silo;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $cuve;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matiere;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codejde;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $lot;

  
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datedebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datefin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $atelier;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="controles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $userdebut;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $userfin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $etatdebut = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $etatfin = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $modification;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lotModification;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSilo(): ?string
    {
        return $this->silo;
    }

    public function setSilo(string $silo): self
    {
        $this->silo = $silo;

        return $this;
    }

    public function getCuve(): ?string
    {
        return $this->cuve;
    }

    public function setCuve(string $cuve): self
    {
        $this->cuve = $cuve;

        return $this;
    }

    public function getCodejde(): ?string
    {
        return $this->codejde;
    }

    public function setCodejde(string $codejde): self
    {
        $this->codejde = $codejde;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getLot(): ?string
    {
        return $this->lot;
    }

    public function setLot(string $lot): self
    {
        $this->lot = $lot;

        return $this;
    }



    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getAtelier(): ?string
    {
        return $this->atelier;
    }

    public function setAtelier(string $atelier): self
    {
        $this->atelier = $atelier;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUserdebut(): ?User
    {
        return $this->userdebut;
    }

    public function setUserdebut(?User $userdebut): self
    {
        $this->userdebut = $userdebut;

        return $this;
    }

    public function getUserfin(): ?User
    {
        return $this->userfin;
    }

    public function setUserfin(?User $userfin): self
    {
        $this->userfin = $userfin;

        return $this;
    }

    public function isEtatdebut(): ?bool
    {
        return $this->etatdebut;
    }

    public function setEtatdebut(?bool $etatdebut): self
    {
        $this->etatdebut = $etatdebut;

        return $this;
    }

    public function isEtatfin(): ?bool
    {
        return $this->etatfin;
    }

    public function setEtatfin(bool $etatfin): self
    {
        $this->etatfin = $etatfin;

        return $this;
    }

    public function getModification(): ?string
    {
        return $this->modification;
    }

    public function setModification(?string $modification): self
    {
        $this->modification = $modification;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getLotModification(): ?string
    {
        return $this->lotModification;
    }

    public function setLotModification(?string $lotModification): self
    {
        $this->lotModification = $lotModification;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }


   

   

}
