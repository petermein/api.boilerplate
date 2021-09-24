<?php

declare(strict_types=1);

namespace Api\Domain\Exceptions;

final class ModelNotFoundException extends Exception
{
    public int $statusCode = 404;

    public static function modelNotFound($object, $identifier)
    {
        return new self(
            \Safe\sprintf('Model of class %s with identifier %s not found.', get_class($object), $identifier)
        );
    }
}
