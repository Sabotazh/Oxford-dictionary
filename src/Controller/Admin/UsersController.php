<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/admin/users', methods: ['GET'], name: 'admin_users')]
    public function users(Request $request, UserRepository $repository): Response
    {
        $users = $repository->pagination($request->query->getInt('page', 1), 15);

        return $this->render('pages/admin/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route('/admin/users/user/{id}/bann', name: 'admin_users_user_bann')]
    public function bannUser(int $id, UserRepository $repository): RedirectResponse
    {
        try {
            $user = $repository->find($id);
            $user->setIsBanned(true);
            $repository->save($user, true);
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/users/user/{id}/unbann', name: 'admin_users_user_unbann')]
    public function unbannUser(int $id, UserRepository $repository): RedirectResponse
    {
        try {
            $user = $repository->find($id);
            $user->setIsBanned(false);
            $repository->save($user, true);
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $this->redirectToRoute('admin_users');
    }
}
