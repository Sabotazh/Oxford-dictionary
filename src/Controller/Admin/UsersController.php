<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\UserRepository;

class UsersController extends AbstractController
{
    private UserRepository $userRepository;

    /**
     * @var FavoriteRepository
     */
    public function __construct(
        UserRepository $userRepository
    )
    {
       $this->userRepository = $userRepository;
    }

    /**
     * @return Response
     */
    #[Route('/admin/users', methods: ['GET'], name: 'users')]
    public function users(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('pages/admin/users.html.twig', [
            'users' => $users
        ]);
    }
}
