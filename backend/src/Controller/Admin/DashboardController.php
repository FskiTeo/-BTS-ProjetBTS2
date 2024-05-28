<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use App\Entity\Article;
use App\Entity\ArticlePicture;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Color;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Shipping;
use App\Entity\Sizes;
use App\Entity\Stock;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        return $this->render('admin/index.html.twig');


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(ClientCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ACME DashBoard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Addresses', 'fas fa-home', Address::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-box', Article::class);
        yield MenuItem::linkToCrud('ArticlePictures', 'fas fa-image', ArticlePicture::class);
        yield MenuItem::linkToCrud('Carrier', 'fas fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-box', Category::class);
        yield MenuItem::linkToCrud('Clients', 'fas fa-person', Client::class);
        yield MenuItem::linkToCrud('Colors', 'fas fa-palette', Color::class);
        yield MenuItem::linkToCrud('Order', 'fas fa-envelope', Order::class);
        yield MenuItem::linkToCrud('Payments', 'fas fa-sack-dollar', Payment::class);
        yield MenuItem::linkToCrud('Shipping', 'fas fa-envelope-circle-check', Shipping::class);
        yield MenuItem::linkToCrud('Sizes', 'fas fa-up-right-and-down-left-from-center', Sizes::class);
        yield MenuItem::linkToCrud('Stock', 'fas fa-layer-group', Stock::class);

    }
}
