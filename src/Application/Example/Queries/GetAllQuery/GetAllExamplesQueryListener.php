<?php

declare(strict_types=1);

namespace Api\Application\Example\Queries\GetAllQuery;

use Api\Common\Bus\Abstracts\ListenerAbstract;
use Symfony\Component\Messenger\Envelope;

/**
 * Class GetAllExamplesQueryListener
 * @package Api\Application\Example\Queries\GetAllQuery
 */
final class GetAllExamplesQueryListener extends ListenerAbstract
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
