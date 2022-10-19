<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WordPageController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/query', methods: ['GET'], name: 'query')]
    public function __invoke(): Response
    {
        return $this->render('word.html.twig');
    }
}
