<?php

namespace App\Controller;

use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\TagRepository;

class MainController extends AbstractController
{
    private TagRepository $tagRepository;

    public function __construct(
        TagRepository $tagRepository
    )
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @return Response
     */
    #[Route('/', methods: ['GET'], name: 'main')]
    public function __invoke(): Response
    {
        $renderParam['form'] = $this->createForm(SearchFormType::class)->createView();

        try {
            $renderParam['tags'] = $this->tagRepository->findBy([], ['id' => 'desc'], 50);
        } catch (\Exception $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        }

        return $this->render('pages/main.html.twig', $renderParam);
    }
}
