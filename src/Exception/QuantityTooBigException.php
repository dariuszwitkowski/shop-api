<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class QuantityTooBigException extends \RuntimeException
{
    const ERROR_MESSAGE = "Quantity to big for product: ";
    public function __construct($productName)
    {
        $message = self::ERROR_MESSAGE. $productName;
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }
}
