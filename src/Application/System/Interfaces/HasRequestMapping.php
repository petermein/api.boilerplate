<?php

declare(strict_types=1);

namespace Api\Application\System\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface HasRequestMapping
{
    public function requestMapping(Request $request): self;
}
