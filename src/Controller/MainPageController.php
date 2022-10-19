<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', methods: ['GET'], name: 'main')]
    public function __invoke(): Response
    {
        $form = $this->createForm(SearchFormType::class);

        return $this->render('main.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
