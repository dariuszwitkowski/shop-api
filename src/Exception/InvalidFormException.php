<?php


namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class InvalidFormException extends \RuntimeException
{
    public function __construct(FormInterface $form)
    {
        parent::__construct($form->getErrors(true)->current()->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
