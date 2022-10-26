<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/dashboard', methods: ['GET'], name: 'dashboard')]
    public function __invoke(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}
