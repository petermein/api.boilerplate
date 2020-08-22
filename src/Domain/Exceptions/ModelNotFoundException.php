<?php

declare(strict_types=1);

namespace Api\Domain\Exceptions;


final class ModelNotFoundException extends \RuntimeException
{
    public static function modelNotFound($object, $identifier)
    {
        return new static(
            \Safe\sprintf('Model of class %s with identifier %s not found.', get_class($object), $identifier)
        );
    }

}