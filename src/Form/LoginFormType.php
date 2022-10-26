<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'       => 'Email',
                'required'    => true,
                'constraints' => [
                    new Email(),
                ],
                'attr'  => [
                    'class'         => 'form-control-lg',
                    'placeholder'   => 'Input email',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label'    => 'Password',
                'required' => true,
                'constraints' => [
                    new Length(null, 8, 255),
                ],
                'attr'  => [
                    'class'         => 'form-control-lg',
                    'placeholder'   => 'Input password',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Login',
                'attr'  => [
                    'class' => 'btn-outline-light btn-lg px-5',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
