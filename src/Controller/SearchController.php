<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Service\DictionaryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private DictionaryService $service;

    public function __construct(DictionaryService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('search/query', methods: 'GET', name: 'search.result' )]
    public function __invoke(Request $request): Response
    {
        $search = $request->query->get('search');

        $renderParam['form'] = $this->createForm(
            SearchFormType::class,
            ['attr' => ['value' => $search]]
        )->createView();

        try {
            $renderParam['result'] = $this->service->getEnteries('en-de', $search);
        } catch (\ErrorException $exception) {
            $renderParam['error'] = $exception->getMessage();
        }

        try {
            $renderParam['history'] = []; // TODO customer history
        } catch (\Exception $exception) {
            $renderParam['error'] = $exception->getMessage();
        }

        return $this->render('search.html.twig', $renderParam);
    }
}
