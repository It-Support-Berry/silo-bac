<?php

namespace App\Service;

use DateInterval;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class FiltreService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Méthode pour gérer le traitement du formulaire de recherche 
     */
    public function submitForm(FormInterface $form, $repository, $type)
    {
        $dataForm = $form->getData();
        $search = $dataForm['search'];
        $dateStart = $dataForm['dateStart'];
        $dateEnd = $dataForm['dateEnd'];

        $this->sessionData($search, $dateStart, $dateEnd);

        if ($search !== null || $dateStart !== null || $dateEnd !== null) {
            if ($dateEnd !== null) {
                $dateEnd->add(new DateInterval('P1D'));
            }
            return $repository->$type($search, $dateStart, $dateEnd);
        }
    }

    /**
     * Mettre en session les données variables pour l'impression
     */
    private function sessionData($search, $dateStart, $dateEnd)
    {
        $session = $this->requestStack->getSession();
        $session->set('search', $search);
        $session->set('dateStart', $dateStart);
        $session->set('dateEnd', $dateEnd);
    }
}