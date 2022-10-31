<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\SearchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    private SearchRepository $searcheRepository;

    public function __construct(SearchRepository $searcheRepository)
    {
        $this->searcheRepository = $searcheRepository;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/', methods: ['GET'], name: 'main')]
    public function __invoke(): Response
    {
        $renderParam['form'] = $this->createForm(SearchFormType::class)->createView();

        // data for the history section
        $renderParam['history'] = $this->searcheRepository->findBy([], ['id' => 'desc'], 5);

        // data for the tags cloud section
        $renderParam['tags'] = $this->searcheRepository->findBy([], ['count' => 'desc'], 50);

        return $this->render('pages/main.html.twig', $renderParam);
    }
}
