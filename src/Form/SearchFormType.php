<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class SearchFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction('search/query')
            ->setMethod('GET')
            ->add('search', TextType::class, [
                'label' => false,
                'required' => true,
                'constraints' => [
                    new Length(null, 1, 255),
                ],
                'attr'  => [
                    'placeholder'   => 'Input word',
                    'value'         => $options['data']['attr']['value'] ?? '',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Search',
                'attr'  => [
                    'class' => 'btn-success mb-2'
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
