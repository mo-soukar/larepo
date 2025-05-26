<?php

namespace Soukar\Larepo\Exceptions;

use Exception;

class FileAlreadyExistsException extends Exception
{

    public function __construct(string $filename, string $message = "", int $code = 0, ?\Throwable $previous = NULL)
    {
        $message = $filename . " Already Exists";
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
