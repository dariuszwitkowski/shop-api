<?php


namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CartExistenceException extends \RuntimeException
{
    const ERROR_MESSAGE_EXISTS = "Cart already exists";
    const ERROR_MESSAGE_NOT_EXISTS = "Cart does not exists";
    public function __construct(bool $shouldExist)
    {
        if($shouldExist)
            $message = self::ERROR_MESSAGE_NOT_EXISTS;
        else
            $message = self::ERROR_MESSAGE_EXISTS;
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }
}
