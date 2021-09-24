<?php

declare(strict_types=1);

namespace Api\Common\Bus\Interfaces;

use Symfony\Component\Messenger\Envelope;

/**
 * Interface ValidatorLocatorInterface
 * @package Api\Common\Bus\Interfaces
 */
interface ValidatorLocatorInterface
{
    /**
     * @param Envelope $envelope
     * @return iterable
     */
    public function getValidators(Envelope $envelope): iterable;
}
