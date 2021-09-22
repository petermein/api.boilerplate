<?php


namespace Api\Common\OpenApi\Traits;


trait HasStatusCodeTrait
{
    protected int $statusCode;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
