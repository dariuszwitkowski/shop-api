<?php


namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class EntityNotFountException extends \RuntimeException
{
    const ERROR_MESSAGE = "Requested entity not found";

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE, Response::HTTP_NOT_FOUND);
    }
}
