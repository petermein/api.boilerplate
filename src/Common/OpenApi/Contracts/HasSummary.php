<?php

namespace Api\Common\OpenApi\Contracts;

interface HasSummary
{
    public function getSummary(): string;
}
