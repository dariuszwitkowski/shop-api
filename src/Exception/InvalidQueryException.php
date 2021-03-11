<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidQueryException extends \RuntimeException
{
    const ERROR_MESSAGE = "Invalid query";
    public function __construct($message = self::ERROR_MESSAGE, $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
