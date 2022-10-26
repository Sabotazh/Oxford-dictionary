<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Favorites;

class FavoritesController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    /**
     * @return Response
     */
    #[Route('/favorites', methods: 'GET', name: 'favorites')]
    public function favorites(): Response
    {
        return $this->render('favorites.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/favorites', methods: 'POST', name: 'save.favorites')]
    public function saveFavorites(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->security->getUser();

        $repository = $doctrine->getRepository(Favorites::class);
        $favorites = $repository->findOneBy(['word_id' => $request->getContent()]);

        if(!$favorites) {
            $favorites = new Favorites();
            $favorites->setCount(1);
            $favorites->setCreatedAt();
        } else {
            $favorites->setCount($favorites->getCount() + 1);
            $favorites->setUpdatedAt();
        }

        $entityManager = $doctrine->getManager();

        $favorites->setWordId($request->getContent());
        $favorites->setUserId($user->getId());
        $entityManager->persist($favorites);
        $entityManager->flush();

        return new Response('The word was saved');
    }
}
