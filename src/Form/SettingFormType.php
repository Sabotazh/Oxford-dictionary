<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Security\Core\Security;

class SettingFormType extends AbstractType
{
    private $security;
    /**
     * @var Security
     * @var FavoriteRepository
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->setAction('/user/profile')
            ->setMethod('POST')
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new Length(null, 1, 255),
                ],
                'attr'  => [
                    'placeholder'   => 'Input name',
                    'value'         => $user->getName(),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints' => [
                    new Length(null, 1, 255),
                ],
                'attr'  => [
                    'placeholder'   => 'Input email',
                    'value'         => $user->getEmail(),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label'    => 'Password',
                'required' => false,
                'constraints' => [
                    new Length(null, 8, 255),
                ],
                'attr'  => [
                    'placeholder'   => 'Input password',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr'  => [
                    'class' => 'btn btn-primary'
                ],
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
