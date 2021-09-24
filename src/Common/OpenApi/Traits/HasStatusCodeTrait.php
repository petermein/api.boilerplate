<?php

namespace Api\Common\OpenApi\Traits;

trait HasStatusCodeTrait
{
    protected int $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
