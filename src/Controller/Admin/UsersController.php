<?php

namespace App\Controller\Admin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UsersController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/admin/users', methods: ['GET'], name: 'users')]
    public function users(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('pages/admin/users.html.twig', [
            'users' => $users
        ]);
    }
}
