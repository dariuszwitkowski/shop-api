<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class GuestExistenceException extends \RuntimeException
{
    const ERROR_MESSAGE_EXISTS = "Guest already exists";
    const ERROR_MESSAGE_NOT_EXISTS = "Guest does not exists";
    public function __construct($shouldExist)
    {
        if($shouldExist)
            $message = self::ERROR_MESSAGE_NOT_EXISTS;
        else
            $message = self::ERROR_MESSAGE_EXISTS;
        parent::__construct($message, Response::HTTP_BAD_REQUEST);
    }
}
