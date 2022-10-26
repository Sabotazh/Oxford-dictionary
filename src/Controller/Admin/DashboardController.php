<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/admin/dashboard', methods: ['GET'], name: 'dashboard')]
    public function __invoke(): Response
    {
        return $this->render('pages/admin/dashboard.html.twig');
    }
}
