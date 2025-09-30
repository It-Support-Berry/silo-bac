<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Form\ControlCreateType;
use App\Form\ControlValidationType;
use App\Form\ControlUpdateType;
use App\Repository\CodejdeRepository;
use App\Repository\ControleRepository;
use App\Repository\CuveRepository;
use App\Repository\SiloRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\EmailService; 
use App\Service\PdfService;

class SiloController extends AbstractController
{
    private $manager;
    private $emailService;
    private $controlRepository;
    private const ITEMS_PER_PAGE =  12;

    public function __construct(
        EntityManagerInterface $manager, 
        EmailService  $emailService, 
        ControleRepository $controlRepository, 
        )
    {
        $this->manager              = $manager;
        $this->emailService         =  $emailService;
        $this->controlRepository   =  $controlRepository;

    }

    /**
     * Méthode qui affiche la liste des silos en cours d'utilisation
     * @Route("/silos", name="app_silos")
    */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $page = max($request->query->getInt('page',  1),  1);
        $data = $this->controlRepository->findBy(['etatfin' => 0], ['cuve' =>'asc']);

        $controls = $paginator->paginate(
            $data,
            $page,
            self::ITEMS_PER_PAGE
        );

        return $this->render('silo/index.html.twig', [
            'controls'         => $controls,
        ]);
    }

    /**
     * Methode quipermet de voir les détail d'un enregistrement de silo 
     * @Route("/silo/details/{id}", name="silo_details")
     */
    public function silo_details ($id): Response
    {
        $control = $this->controlRepository->find($id);
        if(!$control){
            return $this->redirectToRoute("app_silos"); 
        }
       
        return $this->render('silo/details.html.twig', [
            'control'         => $control,
        ]);
    }

    /**
     * Methode quipermet de créer un nouveau silo 
     * @Route("/silo/create", name="silo_create")
     */
    public function create( Request $request ): Response
    {
        $control = new Controle();
        $form = $this->createForm(ControlCreateType::class, $control);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $control->setUser($this->getUser());
            $this->manager->persist($control);
            $this->manager->flush();
            return $this->redirectToRoute("app_silos"); 
        }

        return $this->render('silo/create.html.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * Methode qui permet de modifier un enregistrement avec le role de receptionniste 
     * @Route("/silo/update/{id}", name="silo_update")
     */
    public function silo_update( Request $request,  Controle $control ): Response
    {
        $form = $this->createForm(ControlUpdateType::class, $control);
        $form->handleRequest($request);
        $date = new DateTime('now');

        if($form->isSubmitted() && $form->isValid()){

            if($control->isEtatdebut() == false &&  $control->isEtatfin() == true){
                $this->addFlash('success', 'Il faut débuter avant de clôturer');
            }
            elseif($control->isEtatdebut() == false &&  $control->isEtatfin() == false){
                $this->manager->persist($control);
            }
            elseif($control->isEtatdebut() == true &&  $control->isEtatfin() == false){
                $control->setDatedebut($date);
                $control->setUserdebut($this->getUser());
                $this->manager->persist($control);
            }
            elseif($control->isEtatdebut() == true &&  $control->isEtatfin() == true){

                $control->setDateFin($date);
                $control->setUserFin($this->getUser());  
            
                // Préparation des données pour l'email
                $subject = 'Traçabilité : ' .$control->getMatiere() . " - Silo : " . $control->getSilo();
                $htmlTemplate = 'email/closing.html.twig';
                $context = ['control' => $control];

                // Envoyer l'email aux destinataires 
                $this->emailService->sendEmail( $subject, $htmlTemplate, $context);
                $this->manager->persist($control);
            }

            $this->manager->flush();
            return $this->redirectToRoute("app_silos"); 
        }
        return $this->render('silo/update.html.twig', [
            'form' => $form->createView(),
            'control'=> $control,
        ]);
    }

    /**
     * Methode qui permet de modifier un enregistrement avec le role de chef dequipe
     * @Route("/silo/validation/{id}", name="silo_validation")
     */
    public function silo_validation( Request $request, Controle $control ): Response
    {
        $form = $this->createForm(ControlValidationType::class, $control);
        $form->handleRequest($request);
        $date = new DateTime('now');

        if($form->isSubmitted() && $form->isValid()){
            
            if($control->isEtatdebut() == 0 &&  $control->isEtatfin() == 1){
                $this->addFlash('success', 'Il faut débuter avant de clôturer');
            }
            elseif($control->isEtatdebut() == 0 &&  $control->isEtatfin() == 0){

                $control->setDatedebut(NULL);
                $control->setUserdebut(NULL);
            }
            elseif($control->isEtatdebut() == 1 &&  $control->isEtatfin() == 0){

                $control->setDatedebut($date);
                $control->setUserdebut($this->getUser());
               
                // Préparation des données pour l'email
                $subject = 'Traçabilité : ' .$control->getMatiere() . " - Silo : " . $control->getSilo();
                $htmlTemplate = 'email/start.html.twig';
                $context = ['control' => $control];

                // Envoyer l'email aux destinataires 
                $this->emailService->sendEmail( $subject, $htmlTemplate, $context);
            }
            elseif($control->isEtatdebut() == 1 &&  $control->isEtatfin() == 1){
                $control->setDateFin($date);
                $control->setUserFin($this->getUser());
            }

            $this->manager->persist($control);
            $this->manager->flush();
            return $this->redirectToRoute("app_silos"); 
        
        }
        return $this->render('silo/validation.html.twig', [
            'form' =>$form->createView(), 
        ]);
    }

    /**
     * Methode qui permet de télécharger l'ensemble des silos en cours
     * @Route("/silo/download", name="silo_download")
    */
    public function silo_download ( PdfService $pdfService ): Response
    {
        $controls = $this->controlRepository->findBy(['etatfin' =>0], ['cuve' =>'asc']);

        $html = $this->renderView('silo/download.html.twig', [
            'controls' => $controls
        ]);

        return $pdfService->generatePdf($html, 'silos.pdf');
    }

    /**
     * Méthode qui permet de récupérer la cuve a partir du select d'un silo
     * @Route("/getCuves", name="getCuves")
    */
    public function getCuves( Request $request, SiloRepository $siloRepository, CuveRepository $cuveRepository )
    {
        $cuves = $cuveRepository->createQueryBuilder("c")
            ->where("c.silo = :siloId")
            ->setParameter("siloId", $request->query->get("siloId"))
            ->getQuery()
            ->getResult();
        
        $silo = $siloRepository->find($request->query->get("siloId"));
        $responseArray = array();
        foreach($cuves as $cuve){
            $responseArray[] = array(
                "id" => $cuve->getId(),
                "numero" => $cuve->getNumero()
            );
        }
        return new JsonResponse(['data'=>$responseArray, 'atelier'=>$silo->getAtelier()->getNom()]); 
    }

    /**
     * Méthode qui permet de récupérer la matière a partir du select du codejde
     * @Route("/getMatiere", name="getMatiere")
     */
    public function getMatiere( Request $request, CodejdeRepository $codejdeRepository )
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
                "code" => $codejde->getCode()
            );            
        }
        return new JsonResponse(['data'=>$responseArray]);
        dd($responseArray);
    }
}
