<?php

declare(strict_types=1);


namespace Api\Common\Bus\Interfaces;

/**
 * Interface RequestInterface
 * @package Api\Common\Bus\Interfaces
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return string|null
     */
    public function handler(): ?string;

    /**
     * @return array
     */
    public function validators(): array;

    /**
     * @return array
     */
    public function senders(): array;
}
