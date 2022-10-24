<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/favorites', methods: ['GET'], name: 'favorites')]
    public function __invoke(): Response
    {
        return $this->render('favorites.html.twig');
    }
}
