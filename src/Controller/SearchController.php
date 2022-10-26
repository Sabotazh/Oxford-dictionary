<?php

namespace App\Controller;

use App\Exception\ApiExecutionException;
use App\Exception\BuilderNotFoundException;
use App\Exception\ClientNotFoundException;
use App\Exception\DictionaryException;
use App\Factory\BuilderFactory;
use App\Factory\ClientFactory;
use App\Form\SearchFormType;
use App\Service\Dictionary;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private ClientFactory $clientFactory;
    private BuilderFactory $builderFactory;

    public function __construct(ClientFactory $clientFactory, BuilderFactory $builderFactory)
    {
        $this->clientFactory = $clientFactory;
        $this->builderFactory = $builderFactory;
    }

    /**
     * @param string $provider
     * @param Request $request
     * @return Response
     */
    #[Route('/dictionary/{provider}/entries', methods: 'GET', name: 'search.result' )]
    public function entries(string $provider, Request $request): Response
    {
        $renderParam = [];

        $search = $request->query->get('search');

        $renderParam['form'] = $this->createForm(SearchFormType::class, ['attr' => ['value' => $search]])
            ->createView();

        try {
            $client = $this->clientFactory->create($provider);
            $entityBuilder = $this->builderFactory->create($provider);
            $renderParam['entry'] = (new Dictionary($client, $entityBuilder))->getEnteries('en-gb', $search);
        } catch (ClientNotFoundException $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        } catch (BuilderNotFoundException $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        } catch (DictionaryException $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        } catch (ApiExecutionException $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        } catch (\Exception $exception) {
            $renderParam['errors'][] = 'Some error has occurred. Please try again or contact an administrator.';
        }

        try {
            $renderParam['history'] = []; // TODO customer history
        } catch (\Exception $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        }

        try {
            $renderParam['tags'] = []; // TODO include tags data
        } catch (\Exception $exception) {
            $renderParam['errors'][] = $exception->getMessage();
        }

        return $this->render('pages/search.html.twig', $renderParam);
    }
}
