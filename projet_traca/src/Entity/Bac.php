<?php

namespace App\Entity;

use App\Repository\BacRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BacRepository::class)
 */
class Bac
{
    public function __construct()
    {
        $this->CreatedAt = new \DateTime();
        $this->UpdatedAt = new \DateTime();
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $numerobac;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $localisation;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $matiere;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $codejde;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lot1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lot2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantite2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $UpdatedAt;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bac")
     */
    private $user;


    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat = true;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $cariste;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCodejde(): ?string
    {
        return $this->codejde;
    }

    public function setCodejde(string $codejde): self
    {
        $this->codejde = $codejde;

        return $this;
    }

    public function getLot1(): ?string
    {
        return $this->lot1;
    }

    public function setLot1(?string $lot1): self
    {
        $this->lot1 = $lot1;

        return $this;
    }


    public function getLot2(): ?string
    {
        return $this->lot2;
    }

    public function setLot2(?string $lot2): self
    {
        $this->lot2 = $lot2;

        return $this;
    }
  

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of numerobac
     */ 
    public function getNumerobac()
    {
        return $this->numerobac;
    }

    /**
     * Set the value of numerobac
     *
     * @return  self
     */ 
    public function setNumerobac($numerobac)
    {
        $this->numerobac = $numerobac;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get the value of quantite1
     */ 
    public function getQuantite1()
    {
        return $this->quantite1;
    }

    /**
     * Set the value of quantite1
     *
     * @return  self
     */ 
    public function setQuantite1($quantite1)
    {
        $this->quantite1 = $quantite1;

        return $this;
    }

    /**
     * Get the value of quantite2
     */ 
    public function getQuantite2()
    {
        return $this->quantite2;
    }

    /**
     * Set the value of quantite2
     *
     * @return  self
     */ 
    public function setQuantite2($quantite2)
    {
        $this->quantite2 = $quantite2;

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


    /**
     * Get the value of UpdatedAt
     */ 
    public function getUpdatedAt() : ?\DateTimeInterface
    {
        return $this->UpdatedAt;
    }

    /**
     * Set the value of UpdatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt(?\DateTimeInterface $UpdatedAt):self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    /**
     * Get the value of cariste
     */ 
    public function getCariste()
    {
        return $this->cariste;
    }

    /**
     * Set the value of cariste
     *
     * @return  self
     */ 
    public function setCariste($cariste)
    {
        $this->cariste = $cariste;

        return $this;
    }

    /**
     * Get the value of localisation
     */ 
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set the value of localisation
     *
     * @return  self
     */ 
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }
}


