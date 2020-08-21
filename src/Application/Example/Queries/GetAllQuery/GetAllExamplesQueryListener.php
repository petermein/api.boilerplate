<?php

declare(strict_types = 1);

declare(strict_types=1);


namespace Api\Application\Example\Queries\GetAllQuery;


use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

class GetAllExamplesQueryListener implements SenderInterface
{
    public function __construct()
    {
        //get dependencies from laravel ioc
    }

    /**
     * Sends the given envelope.
     *
     * The sender can read different stamps for transport configuration,
     * like delivery delay.
     *
     * If applicable, the returned Envelope should contain a TransportMessageIdStamp.
     *
     * @param Envelope $envelope
     * @return Envelope
     */
    public function send(Envelope $envelope): Envelope
    {
        return $envelope;
    }
}