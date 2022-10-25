<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new Email(),
                    new Length(null, 3, 255),
                ],
                'attr'  => [
                    'class'         => 'form-control-lg',
                    'placeholder'   => 'Input email',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new Length(null, 3, 255),
                ],
                'attr'  => [
                    'class'         => 'form-control-lg',
                    'placeholder'   => 'Input Your name',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'     => [
                    'label' => 'Password',
                    'constraints' => [
                        new Length(null, 8, 255),
                    ],
                    'attr'  => [
                        'class'         => 'form-control-lg',
                        'placeholder'   => 'Input password',
                    ],
                ],
                'second_options'    => [
                    'label' => 'Confirm Password',
                    'attr'  => [
                        'class'         => 'form-control-lg',
                        'placeholder'   => 'Confirm password',
                    ],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
                'attr'  => [
                    'class' => 'btn-outline-light btn-lg px-5',
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
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
