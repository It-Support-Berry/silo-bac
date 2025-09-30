<?php

namespace App\Controller;

use App\Entity\Archives;
use App\Entity\Modification;
use App\Form\FiltreDateType;
use App\Form\ArchiveBacUpdateType;
use App\Repository\ArchivesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdfService;
use App\Service\FiltreService;

class ArchiveBacController extends AbstractController
{

    private $manager;
    private $requestStack;
    private $pdfService;
    private $archiveRepository;
    private $filtreService;
    private const ITEMS_PER_PAGE =  10;

    public function __construct( 
        EntityManagerInterface $manager, 
        RequestStack $requestStack, 
        PdfService $pdfService,
        ArchivesRepository $archiveRepository, 
        FiltreService $filtreService
        )
    {
        $this->manager                  = $manager;
        $this->requestStack             = $requestStack;
        $this->archiveRepository        = $archiveRepository;
        $this->pdfService               = $pdfService;
        $this->filtreService            = $filtreService;
        
    }

    
    /**
     * Methode qui affiche l'ensemble des archives avec ou sans filtre
     * @Route("/archive/bac", name="app_archive_bac")
     */
    public function index( Request $request, PaginatorInterface $paginator ): Response
    {
        $page = max($request->query->getInt('page',  1),  1);
        $data = $this->archiveRepository->findBy(['etat'=>true],['date' =>'desc']);
        
        $form = $this->createForm(FiltreDateType::class);
        $form->handleRequest($request);

        // si on décide d'appliquer un filtre 
        if($form->isSubmitted() && $form->isValid()){
            $data = $this->filtreService->submitForm($form, $this->archiveRepository, 'search_bac' );   
        }

        $archives = $paginator->paginate(
            $data,
            $page,
            self::ITEMS_PER_PAGE
        );

        return $this->render('archive_bac/index.html.twig', [
            'archives'         => $archives, 
            'form' =>$form->createView()
        ]);
    }


    /**
     * Methode qui permet de créer une modification et de mettre a jour un enregistrement d'un bac 
     * @Route("/archive/bac/update/{id}", name="archive_bac_update")
    */
    public function archive_bac_update( Request $request, Archives $archive ): Response
    {
        $clone = $archive;
        $form = $this->createForm(ArchiveBacUpdateType::class, $archive);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $modification = new Modification(); // Une table qui permet de stocker les anciennes valeurs de l'archive qui doit être modifiée 
            $modification->setNumerobac($clone->getNumerobac());
            $modification->setLocalisation($clone->getLocalisation());
            $modification->setMatiere($clone->getMatiere());
            $modification->setCodejde($clone->getCodejde());
            $modification->setLot1($clone->getLot1());
            $modification->setLot2($clone->getLot2());
            $modification->setDate($clone->getDate());
            $modification->setCariste($clone->getCariste());
            $modification->setQuantite1($clone->getQuantite1());
            $modification->setQuantite2($clone->getQuantite2());
            $modification->setUser($this->getUser());
            $modification->setCommentaire($clone->getCommentaire());
            $modification->setIdentifiant($clone->getId());
            $archive->setTypeOfAction(3);
            
            $this->manager->persist($modification);
            $this->manager->persist($clone);
            $this->manager->flush();
            return $this->redirectToRoute("app_archive_bac");
        }

        return $this->render('archive_bac/update.html.twig', [
            'form' =>$form->createView(), 
        ]);
    }


    /**
     * Methode quipermet de voir les détails d'un enregistrement d'un bac dans la partie archives
     * @Route("/archive/bac/deatils/{id}", name="archive_bac_details")
     */
    public function archive_bac_details ( $id ): Response
    {
        $archive = $this->archiveRepository->find($id);
        if(!$archive){
            return $this->redirectToRoute("app_bac"); 
        }
       
        return $this->render('archive_bac/details.html.twig', [
            'archive'         => $archive,
        ]);
    }


    /**
     * Methode qui permet de télécharger l'ensemble des archives des bacs
     * @Route("/archive/bac/download", name="archive_bac_download")
     */
    public function archive_bac_download (): Response
    {
        $session = $this->requestStack->getSession();
        $search = $session->get('search');   
        $dateStart = $session->get('dateStart');
        $dateEnd = $session->get('dateEnd');

        $archives = $this->archiveRepository->search_bac($search, $dateStart, $dateEnd);
           
        $html = $this->renderView('archive_bac/download.html.twig', [
            'archives' => $archives
        ]);

        return $this->pdfService->generatePdf($html, 'archive_bacs.pdf');
    }


    /**
     * Methode qui permet de télécharger les détails de l'archive
     * @Route("/archive/bac/details/{id}", name="archive_bac_details_download")
     */
    public function archive_bac_details_download ( $id ): Response
    {
        $archive = $this->archiveRepository->find($id);
        if(!$archive){
            return $this->redirectToRoute("app_bac"); 
        }       

        $html = $this->renderView('archive_bac/details_download.html.twig', [
            'archive' => $archive
        ]);

        return $this->pdfService->generatePdf($html, 'détails_archive.pdf');
    }
}
