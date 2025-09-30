<?php

namespace App\Requete;

use App\Entity\Localisation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;

class ConfigRequete  extends AbstractType{

    private $em;   


    public function __construct(EntityManagerInterface $em)
    {
        $this->em                   = $em; 
    }


    /**
     * Methode qui permet de récupérer la liste des emplacements 
     */
    public function getLocalisation()
    {
        $localisations = $this->em->getRepository(Localisation::class)->findBy([], ['localisation'=>'asc']);
        $tabLocalisation = [];

        foreach ($localisations as  $localisation) {
            $tabLocalisation[$localisation->getLocalisation()] = $localisation->getLocalisation();
        }
        return $tabLocalisation;
    }
}