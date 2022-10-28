<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Service\ChartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @param UserRepository $repository
     * @param ChartService $service
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/admin/dashboard', methods: ['GET'], name: 'admin_dashboard')]
    public function __invoke(UserRepository $repository, ChartService $service): Response
    {
        $usersCount = $repository->countRegisteredUsersByMonth();
        $renderParam['users_chart'] = $service->registredUsersChart($usersCount);

        return $this->render('pages/admin/dashboard.html.twig', $renderParam);
    }
}
