<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\FormErrors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route(path: '/registration', name: 'app_registration')]
    public function __invoke(): Response
    {
        $form = $this->createForm(RegistrationFormType::class, new User(), [
            'action' => $this->generateUrl('app_registration_register'),
            'method' => 'POST',
        ]);

        $params['form'] = $form->createView();

        return $this->render('security/registration.html.twig', $params);
    }

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param UserPasswordHasherInterface $hasher
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    #[Route(path: '/registration/register', methods: 'post', name: 'app_registration_register')]
    public function register(
        Request $request,
        UserRepository $repository,
        UserPasswordHasherInterface $hasher,
        UserAuthenticatorInterface $authenticator,
        LoginFormAuthenticator $formAuthenticator
    ): RedirectResponse
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setRoles([User::ROLE_USER])
                ->setPassword($hasher->hashPassword($user, $user->getPassword()))
                ->setCreatedAt()
                ->setUpdatedAt();

            $repository->save($user, true);

            return $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request
            );
        }

        $errors = FormErrors::getErrors($form);

        $this->addFlash('errors', $errors);
        $this->addFlash('old', [
            'name'  => $request->request->get('name'),
            'email' => $request->request->get('email'),
        ]);

        return $this->redirectToRoute('app_registration');
    }
}
