<?php


namespace Api\Common\OpenApi\Traits;


trait HasStatusCodeTrait
{
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
