<?php

namespace Soukar\Larepo\Exceptions;

use Exception;

class ModelDoesNtExistsException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = NULL)
    {
        $message = "Model doesn't exists.";
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
