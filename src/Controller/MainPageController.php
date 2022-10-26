<?php

namespace App\Controller;

use App\Client\OxfordClient;
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
        $renderParam['form'] = $this->createForm(SearchFormType::class)->createView();

        try {
            $renderParam['tags'] = []; // TODO include tags data
        } catch (\Exception $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        }

        return $this->render('main.html.twig', $renderParam);
    }
}
