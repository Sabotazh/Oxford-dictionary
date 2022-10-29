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
use App\Service\FormErrors;

class ProfileController extends AbstractController
{
    private Security $security;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('user/profile', methods: 'GET', name: 'user_profile')]
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

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('user/profile', methods: 'POST', name: 'user_profile_update')]
    public function updateProfile(
        Request $request, 
        UserPasswordHasherInterface $hasher
    ): Response
    {
        $user = $this->security->getUser();
        $data = $request->request->all();

        try {
            $user = $this->userRepository->find($user->getId());
            $form = $this->createForm(SettingFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setName($data['name']);
                $user->setEmail($data['email']);
                if($data['password']) {
                    $user->setPassword($hasher->hashPassword($user, $data['password']));
                }
                $this->userRepository->save($user, true);
                $this->addFlash('alert_success', 'Your profile data has been successfully saved.');
            } else {
                $errors = FormErrors::getErrors($form);
                $this->addFlash('errors', $errors);
                $this->addFlash('alert_error', 'Error saving new profile data.');
            }
        } catch (\Exception $exception) {
            $this->addFlash('alert_error', 'Error saving new profile data.');
        }

        return $this->redirectToRoute('user_profile');
    }
}
