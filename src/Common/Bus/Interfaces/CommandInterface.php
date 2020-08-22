<?php

declare(strict_types=1);


namespace Api\Common\Bus\Interfaces;

interface CommandInterface
{
    public function getData(): array;

    public function handler(): ?string;

    public function validators(): array;

    public function senders(): array;
}
