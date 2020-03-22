<?php

namespace Api\Infrastructure\Bus\Interfaces;

use Symfony\Component\Messenger\Envelope;

interface ValidatorLocatorInterface
{
    public function getValidators(Envelope $envelope): iterable;
}