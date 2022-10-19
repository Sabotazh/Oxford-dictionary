<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('search/query', methods: 'GET', name: 'search.result' )]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(
            SearchFormType::class,
            ['attr' => ['value' => $request->query->get('search')]]
        );

        $result = []; // TODO search result
        $history = []; // TODO customer history

        return $this->render('search.html.twig', [
            'form'   => $form->createView(),
            'result'  => $result,
            'history' => $history,
        ]);
    }
}
