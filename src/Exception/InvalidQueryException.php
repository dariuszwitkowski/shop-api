<?php


namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidQueryException extends \RuntimeException
{
    const ERROR_MESSAGE = "Invalid GET query";
    public function __construct($message = self::ERROR_MESSAGE, $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
