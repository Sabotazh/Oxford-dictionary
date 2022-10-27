<?php

namespace App\Controller;

use App\Exception\ApiExecutionException;
use App\Exception\BuilderNotFoundException;
use App\Exception\ClientNotFoundException;
use App\Exception\DictionaryException;
use App\Factory\BuilderFactory;
use App\Factory\ClientFactory;
use App\Form\SearchFormType;
use App\Repository\HistoryRepository;
use App\Repository\SearchRepository;
use App\Service\Dictionary;
use App\Service\HistoryService;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private ClientFactory $clientFactory;
    private BuilderFactory $builderFactory;
    private SearchRepository $searcheRepository;
    private SearchService $searchService;

    public function __construct(
        ClientFactory    $clientFactory,
        BuilderFactory   $builderFactory,
        SearchRepository $searcheRepository,
        SearchService    $searchService,
    )
    {
        $this->clientFactory = $clientFactory;
        $this->builderFactory = $builderFactory;
        $this->searcheRepository = $searcheRepository;
        $this->searchService = $searchService;
    }

    /**
     * @param string $provider
     * @param Request $request
     * @return Response
     */
    #[Route('/dictionary/{provider}/entries', methods: 'GET', name: 'dictionary_search_result' )]
    public function entries(string $provider, Request $request): Response
    {
        $renderParam = [];
        $desired = $request->query->get('search');
        $renderParam['form'] = $this->createForm(SearchFormType::class, ['attr' => ['value' => $desired]])
            ->createView();

        // get data from external api
        try {
            $client = $this->clientFactory->create($provider);
            $entityBuilder = $this->builderFactory->create($provider);
            $renderParam['entry'] = (new Dictionary($client, $entityBuilder))->getEnteries('en-gb', $desired);
        } catch (ClientNotFoundException | BuilderNotFoundException | DictionaryException | ApiExecutionException $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        } catch (\Exception $exception) {
            $renderParam['errors'][] = 'Some error has occurred. Please try again or contact an administrator.';
        }

        // save new word in searches table or increment the counter
        try {
            $this->searchService->checkWord($desired);
        } catch (\Exception $exception) {
            $renderParam['errors'][] = 'Some error has occurred with check history.';
        }

        // data for the history section
        $renderParam['history'] = $this->searcheRepository->findBy([], ['id' => 'desc'], 5);

        // data for the tags cloud section
        $renderParam['tags'] = $this->searcheRepository->findBy([], ['id' => 'desc'], 50);

        return $this->render('pages/search.html.twig', $renderParam);
    }
}
