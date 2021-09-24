<?php

declare(strict_types=1);

namespace Api\Domain\Exceptions;

use RuntimeException;

abstract class Exception extends RuntimeException
{
    public int $statusCode = 500;
}
