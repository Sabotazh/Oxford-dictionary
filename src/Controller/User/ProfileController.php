<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('user/profile', name: 'profile')]
    public function __invoke(): Response
    {
        return $this->render('pages/user/profile.html.twig');
    }
}
