<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;

class FormErrors
{
    /**
     * @param FormInterface $form
     * @return array
     */
    public static function getErrors(FormInterface $form): array
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = self::getErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
