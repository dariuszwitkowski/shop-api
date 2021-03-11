<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class TooManyProductsInCartException extends \RuntimeException
{
    const ERROR_MESSAGE = "Too many products in cart.";
    public function __construct()
    {
        $message = self::ERROR_MESSAGE;
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }
}
