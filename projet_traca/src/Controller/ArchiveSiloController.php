<?php

namespace App\Controller;

use App\Entity\Controle;
use App\Form\ArchiveSiloUpdateType;
use App\Form\FiltreDateType;
use App\Repository\ControleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\PdfService;
use App\Service\FiltreService;

class ArchiveSiloController extends AbstractController
{

    private $manager;
    private $requestStack;
    private $controleRepository;
    private $pdfService;
    private $filtreService;
    private const ITEMS_PER_PAGE =  12;

    public function __construct( 
        EntityManagerInterface $manager, 
        RequestStack $requestStack, 
        ControleRepository $controleRepository,
        PdfService $pdfService, 
        FiltreService $filtreService
        )
    {
        $this->manager                  = $manager;
        $this->requestStack             = $requestStack;
        $this->controleRepository       = $controleRepository;
        $this->pdfService               = $pdfService;
        $this->filtreService            = $filtreService;
    }
    
    /**
     * Methode qui permet d'affciher les silos enregistré comme archives
     * @Route("/archive/silo", name="app_archive")
     * @return PaginationInterface
     */
    public function index( Request $request, PaginatorInterface $paginator ): Response
    {
        $page = max($request->query->getInt('page',  1),  1);
        $data = $this->controleRepository->findBy(['etatfin' =>1], ['datefin' =>'desc']);

        $form = $this->createForm(FiltreDateType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
           
            $data = $this->filtreService->submitForm($form, $this->controleRepository, 'search_silo' );
        }

        $controls = $paginator->paginate(
            $data,
            $page,
            self::ITEMS_PER_PAGE
        );

        return $this->render('archive_silo/index.html.twig', [
            'controls'         => $controls,
            'form' =>$form->createView()
        ]);
    }
    
    /**
     * Methode qui permet de modifier l'archives des silos avec le role de d'admin
     * @Route("/archive/silo/update/{id}", name="archive_silo_update")
     */
    public function archive_silo_update( $id, Request $request,  Controle $controlRecovered ): Response
    {
        $form = $this->createForm(ArchiveSiloUpdateType::class, $controlRecovered);
        $form->handleRequest($request);

        $control = $this->controleRepository->find($id);
       
        if($form->isSubmitted() && $form->isValid()){
           
            // On recupere les anciennes valeurs de la date et le lot
            $date = $controlRecovered->getDate()->format('d/m/Y');
            $lot = $controlRecovered->getLot();
    
            //On recupère la date à la quelle la modification a été effectuée
            $dateRegister = date("d/m/Y à H:i:s");
            $user = $this->getUser()->getPrenom() . " ". $this->getUser()->getNom() ;

            if($controlRecovered->getLotModification()=== NULL && $controlRecovered->getDateModification() != NULL){
                
                $control= $controlRecovered->setModification($controlRecovered->getModification() ."\n". "Modifié par : "  . $user. "\n" . "Le : ". $dateRegister . "\n" . "Ancienne date de réception : " . $date . ". ");
    
                // on affecte les nouvelles nouvelles valeurs de date et lot
                $control->setDate($control->getDateModification());

            }

            elseif($controlRecovered->getDateModification() === NULL && $controlRecovered->getLotModification() != NULL){
                $control = $controlRecovered->setModification($controlRecovered->getModification() ."\n". "Modifié par : "  . $user. "\n" . "Le : ". $dateRegister . "\n" . "Ancien lot : " . $lot . ". ");
                
                // on affecte les nouvelles valeurs de date et lot
                $control->setLot($control->getLotModification());

            }
            elseif($controlRecovered->getDateModification() === NULL && $controlRecovered->getLotModification()=== NULL && $controlRecovered->getCommentaire()=== NULL){
                $this->addFlash('success', 'Les champs Date et N° lot sont vides');
            }
            elseif($controlRecovered->getDateModification() === NULL && $controlRecovered->getLotModification()=== NULL && $controlRecovered->getCommentaire() != NULL){
                // on enregistre le commentaire
                $controlRecovered->setCommentaire($controlRecovered->getCommentaire());
            }
            else{
                $control = $controlRecovered->setModification($controlRecovered->getModification() ."\n". "Modifié par : "  . $user. "\n" . "Le : ". $dateRegister . "\n" . "Ancienne date de réception : " . $date. 
                "\n" . "Ancien lot : " . $lot . ". ");
    
                // on affecte les nouvelles nouvelles valeurs de date et lot
                $control->setDate($control->getDateModification());
                $control->setLot($control->getLotModification());
            }

            // on enregistre les médifications
            $this->manager->persist($control);
            $this->manager->flush();
            return $this->redirectToRoute("app_archive"); 
        }

        return $this->render('archive_silo/update.html.twig', [
            'form' =>$form->createView(), 
            'control'         => $control,
        ]);
    }

    /**
     * Methode quipermet de voir les détail d'un enregistrement de silo dans la partie archives pour pour
     * @Route("/archive/silo/details/{id}", name="archive_silo_details")
     */
    public function archive_silo_details ( $id ): Response
    {
        $control = $this->controleRepository->find($id);
        
        if(!$control){
            return $this->redirectToRoute("app_silo"); 
        }
       
        return $this->render('archive_silo/details.html.twig', [
            'control'         => $control,
        ]);
    }

    /**
     * Methode qui permet de télécharger l'ensemble des archives
     * @Route("/archive-download", name="archive_silo_download")
     */
    public function archive_silo_download(): Response
    {
        $controls = $this->controleRepository->findBy(['etatfin' =>1], ['cuve' =>'asc']);

        $session = $this->requestStack->getSession();
        $search = $session->get('search');
        $dateStart = $session->get('dateStart');
        $dateEnd = $session->get('dateEnd');

        $controls = $this->controleRepository->search_silo($search, $dateStart, $dateEnd);
        
        $html = $this->renderView('archive_silo/download.html.twig', [
            'controls' => $controls
        ]);

        return $this->pdfService->generatePdf($html, 'archives.pdf');
    }

   
    /**
     * Methode qui permet de télécharger les détails d'une seule archive
     * @Route("/archive/silo/pdf/{id}", name="archive_silo_details_download")
    */
    public function archive_silo_details_download( $id ) : Response {
        $control = $this->controleRepository->find($id);
        $html = $this->renderView('archive_silo/details_download.html.twig', [
            'control' => $control
        ]);

        return $this->pdfService->generatePdf($html, 'détails_archive.pdf');
    }
}
