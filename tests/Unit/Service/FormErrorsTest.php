<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\FormErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;

final class FormErrorsTest extends KernelTestCase
{
    /**
     * @group unit
     */
    public function testGetErrors(): void
    {
        $formData = [
            'name'      => '',
            'email'     => '',
            'password'  => [
                'first'     => '',
                'second'    => '',
            ]
        ];

        $formFactory = static::getContainer()->get(FormFactoryInterface::class);
        $form = $formFactory->create(RegistrationFormType::class, new User());
        $form->submit($formData);


        $formErrors = new FormErrors();
        $result = $formErrors::getErrors($form);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }
}
