<?php

namespace App\Entity;

use App\Repository\ModificationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModificationRepository::class)
 */
class Modification
{
    public function __construct()
    {
        $this->CreatedAt = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
      * Permet de stocker l'id de l'archive qui a été modifiée
     * @ORM\Column(type="integer")
     */
    private $identifiant;

     /**
     * @ORM\Column(type="string", length=30)
     */
    private $localisation;


    /**
     * @ORM\Column(type="integer")
     */
    private $numerobac;

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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bac")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

   /**
     * @ORM\Column(type="string")
    */
    private $cariste;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLot2(): ?string
    {
        return $this->lot2;
    }

    public function setLot2(?string $lot2): self
    {
        $this->lot2 = $lot2;

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
     * Get the value of CreatedAt
     */ 
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    /**
     * Set the value of CreatedAt
     *
     * @return  self
     */
    public function setCreatedAt(?\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

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
     * Get permet de stocker l'id de l'archive qui a été modifiée
     */ 
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * Set permet de stocker l'id de l'archive qui a été modifiée
     *
     * @return  self
     */ 
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;

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
