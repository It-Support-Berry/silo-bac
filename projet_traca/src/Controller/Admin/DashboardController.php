<?php

namespace App\Controller\Admin;

use App\Entity\Atelier;
use App\Entity\Codejde;
use App\Entity\CodejdeBac;
use App\Entity\Cuve;
use App\Entity\Email;
use App\Entity\Localisation;
use App\Entity\Matiere;
use App\Entity\MatiereBac;
use App\Entity\Silo;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(AtelierCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Traça Astra');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Atelier',           'fas fa-list', Atelier::class);
        yield MenuItem::linkToCrud('CodeJDE Silo',      'fas fa-list', Codejde::class);
        yield MenuItem::linkToCrud('CodeJDE Bac',       'fas fa-list', CodejdeBac::class);
        yield MenuItem::linkToCrud('Cuve',              'fas fa-list', Cuve::class);
        yield MenuItem::linkToCrud('Email de Diffusion',   'fas fa-list', Email::class);
        yield MenuItem::linkToCrud('Localisation Bac',  'fas fa-list', Localisation::class);
        yield MenuItem::linkToCrud('Matière Silo',      'fas fa-list', Matiere::class);
        yield MenuItem::linkToCrud('Matière Bac',       'fas fa-list', MatiereBac::class);
        yield MenuItem::linkToCrud('N° Silo',            'fas fa-list', Silo::class);
        
    }
}
