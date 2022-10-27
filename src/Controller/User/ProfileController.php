<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\FavoriteRepository;
use App\Form\SettingFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    private $security;
    private FavoriteRepository $favoriteRepository;
    private UserRepository $userRepository;
    /**
     * @var Security
     * @var FavoriteRepository
     */
    public function __construct(
        Security $security,
        FavoriteRepository $favoriteRepository,
        UserRepository $userRepository
    )
    {
        $this->security = $security;
        $this->favoriteRepository = $favoriteRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * @return Response
     */
    #[Route('user/profile', methods: 'GET', name: 'profile')]
    public function profile(): Response
    {
        $user = $this->security->getUser();
        $favorites = $this->favoriteRepository->findBy(['user_id' => $user->getId()]);

        $form = $this->createForm(SettingFormType::class)->createView();

        return $this->render('pages/user/profile.html.twig', [
            'user' => $user,
            'favorites' => $favorites,
            'form' => $form
        ]);
    }

    #[Route('user/profile', methods: 'POST', name: 'update.profile')]
    public function updateProfile(
        Request $request, 
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $user = $this->security->getUser();
        $data = $request->request->all();

        try {
            $user = $this->userRepository->find($user->getId());
            $user->setName($data['name']);
            $user->setEmail($data['email']);
            if($data['password']) {
                $user->setPassword($hasher->hashPassword($user, $data['password']));
            }
            $this->userRepository->save($user, true);
        } catch (\Exception $exception) {
            dd($exception);
        }

        return $this->redirectToRoute('profile');
    }
}
