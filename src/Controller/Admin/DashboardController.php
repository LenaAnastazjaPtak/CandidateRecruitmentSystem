<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use App\Entity\JobOffer;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/pages/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Menu');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Job Offers', 'fas fa-stream', JobOffer::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Candidates', 'fas fa-users', Candidate::class);
        yield MenuItem::linktoRoute('Homepage', 'fa fa-arrow-left', 'homepage');
        yield MenuItem::linkToLogout('Logout', "fa fa-sign-out");
    }
}
