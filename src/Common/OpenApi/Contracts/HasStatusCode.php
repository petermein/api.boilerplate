<?php


namespace Api\Common\OpenApi\Contracts;


interface HasStatusCode
{
    public function getStatusCode(): int;
}
