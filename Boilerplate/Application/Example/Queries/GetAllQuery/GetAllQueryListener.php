<?php


namespace Boilerplate\Application\Example\Queries\GetAllQuery;


use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

class GetAllQueryListener implements SenderInterface
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
     */
    public function send(Envelope $envelope): Envelope
    {
        dump('send to outer space');
    }
}