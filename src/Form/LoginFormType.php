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
                    'placeholder'   => 'Input password',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Login',
                'attr'  => [
                    'class' => 'btn-primary px-4',
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
