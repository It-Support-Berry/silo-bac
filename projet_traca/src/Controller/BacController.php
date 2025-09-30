<?php

namespace App\Controller;

use App\Entity\Archives;
use App\Entity\Bac;
use App\Entity\Modification;
use App\Form\BacCreateType;
use App\Form\BacUpdateType;
use App\Repository\ArchivesRepository;
use App\Repository\BacRepository;
use App\Repository\CodejdeBacRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EmailService;
use App\Service\PdfService;

class BacController extends AbstractController
{
    private $bacRepository;
    private $manager;
    private const ITEMS_PER_PAGE =  12;
    private $emailService; 

    public function __construct( EntityManagerInterface $manager, BacRepository $bacRepository, EmailService $emailService)
    {
        $this->bacRepository            = $bacRepository;
        $this->manager                  = $manager;
        $this->emailService             = $emailService;
    }


    /**
     * Methode qui affiche la page d'accueil des Bacs
     * @Route("/", name="app_bac")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $page = max($request->query->getInt('page',  1),  1);
        $data = $this->bacRepository->findBy(['etat'=>true],['numerobac' =>'asc']);

        $bacs = $paginator->paginate(
            $data,
            $page,
            self::ITEMS_PER_PAGE
        ); 

        return $this->render('bac/index.html.twig', [
            'bacs'         => $bacs
        ]);
    }

    /**
     * Methode qui permet de création d'un nouveau lot de bac en envoyé un mail a des utilisateur 
     * 
     * @Route("/bac/create", name="create_bac")
     */
    public function bac_create(Request $request): Response
    {
        $bac = new Bac();
        $typeOfAction = 1;
        $archive = new Archives();
        $form = $this->createForm(BacCreateType::class, $bac);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // On recupère un bac existant
            $oldBac = $this->bacRepository->findOneByNumerobac($bac->getNumerobac());
            // Si le bac existe on le remplace 
            if($oldBac){
            
                $this->updateBacProperties($oldBac, $bac);
                $bac = $oldBac;
                $typeOfAction = 2;
            }

            $archive->setNumerobac($bac->getNumerobac());
            $archive->setTypeOfAction($typeOfAction);

            $this->updateBacProperties($archive, $bac);

            // Préparation des données pour l'email
            $subject = 'Traçabilité Matière: ' . $bac->getMatiere() . " - N° Bac : " . $bac->getNumerobac();
            $htmlTemplate = 'email/create.html.twig';
            $context = ['bac' => $bac];

            // Envoyer l'email aux destinataires 
            $this->emailService->sendEmail($subject, $htmlTemplate, $context);

            $bac->setUser($this->getUser());
            $archive->setUser($this->getUser());
            $this->manager->persist($bac);
            $this->manager->persist($archive);
            $this->manager->flush();
            return $this->redirectToRoute("app_bac"); 
        }

        return $this->render('bac/create.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Methode qui permet de modifier un enregistrement d'un bac 
     * @Route("/bac/update/{id}", name="bac_update")
     * 
    */
    public function bac_update(Request $request, Bac $bac, ArchivesRepository $reposArchives): Response
    {
        $archives = new Archives();
        $archives = $bac;
        $form = $this->createForm(BacUpdateType::class, $bac);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $oldArchive = $reposArchives->findOneByNumerobac($archives->getNumerobac()); // pour récupérer les anciennes informations du bac en cours
            
            if($oldArchive){

                $modification = new Modification(); // Une table qui permet de stocker les anciennes valeurs de l'archive qui doit être modifiée 
                $modification->setNumerobac($oldArchive->getNumerobac());

                $this->updateBacProperties($modification, $oldArchive);
                $modification->setCommentaire($oldArchive->getCommentaire());
                $modification->setIdentifiant($oldArchive->getId());

                // On verifie si le n° de lot a changer on envoie un mail
                if($bac->getLot1() != $oldArchive->getLot1() || $bac->getLot2() != $oldArchive->getLot2()){

                    // Préparation des données pour l'email
                    $subject = 'Mise à jour du lot dans le Bac N°' . $bac->getNumerobac();
                    $htmlTemplate = 'email/update.html.twig';
                    $context = ['bac' => $bac];

                    // Envoyer l'email aux destinataires 
                    $this->emailService->sendEmail($subject, $htmlTemplate, $context);
                }

                // On modifie les anciennes informations
                $this->updateBacProperties($modification, $oldArchive);
                $oldArchive->setCommentaire($bac->getCommentaire());
                $oldArchive->setTypeOfAction(3);

                // Avant d'enregistrer on récupérer l'ancienne valeur de l'archive
                $this->manager->persist($modification);
                $this->manager->persist($oldArchive);
                $this->manager->flush();
                return $this->redirectToRoute("app_bac");
            }
        }

        return $this->render('bac/update.html.twig', [
            'form' =>$form->createView(), 
        ]);
    }


    /**
     * Cette fonction copie les propriétés de l'objet source vers l'objet cible
     * Si numéro de bac saisi existe déjà, alors on le remplace et on copie les anciennes informations vers l'archive
     *
     * @param Bac $target L'objet Bac cible dont les propriétés seront mises à jour.
     * @param Bac $source L'objet Bac source dont les propriétés seront copiées.
     */
    private function updateBacProperties($target, $source)
    {
        $target->setLocalisation($source->getLocalisation());
        $target->setMatiere($source->getMatiere());
        $target->setCodejde($source->getCodejde());
        $target->setLot1($source->getLot1());
        $target->setLot2($source->getLot2());
        $target->setDate($source->getDate());
        $target->setCariste($source->getCariste());
        $target->setQuantite1($source->getQuantite1());
        $target->setQuantite2($source->getQuantite2());
        $target->setUser($this->getUser());
        $target->setCommentaire("");
    }

    /**
     * Methode qui permet de voir les détails d'un bac
     * @Route("/bac/details/{id}", name="bac_details")
    */
    public function bac_details ($id, BacRepository $repoBac): Response
    {
        $bac = $repoBac->find($id);
        if(!$bac){
            return $this->redirectToRoute("app_bac"); 
        }
       
        return $this->render('bac/details.html.twig', [
            'bac'         => $bac,
        ]);
    }

    /**
     * Methode qui permet de télécharger l'ensemble des archives des bac
     * @Route("/bac/download", name="bac_download")
    */
    public function bac_download (PdfService $pdfService): Response
    {
        $bacs = $this->bacRepository->findBy(['etat'=>true],['numerobac' =>'asc']);

        $html = $this->renderView('bac/download.html.twig', [
            'bacs' => $bacs
        ]);

        return $pdfService->generatePdf($html, 'bacs.pdf');
    }

    /**
     * Méthode qui permet de récupérer le codejde a partir du select d'une matiere
     * @Route("/getCodejdeSac", name="getCodejdeSac")
    */
    public function getCodejdeSac(Request $request, CodejdeBacRepository $codejdeRepository)
    {
        $codejdes = $codejdeRepository
            ->createQueryBuilder('c')
            ->select('c')
            ->where('c.matiere = :matiereId')
            ->setParameter('matiereId', $request->query->get("matiereId"))
            ->getQuery()
            ->getResult();

        $responseArray = array();
        foreach($codejdes as $codejde){
            $responseArray[] = array(
                "id" => $codejde->getId(),
                "nom" => $codejde->getNom()
            );
        }
        return new JsonResponse(['data'=>$responseArray]);
    }
}
