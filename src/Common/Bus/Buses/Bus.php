<?php

declare(strict_types=1);

namespace Api\Common\Bus\Buses;

use Api\Common\Bus\Interfaces\RequestInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class QueryBus
 * @package Api\Common\Bus\Buses
 */
final class Bus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param RequestInterface $request
     *
     * @return mixed The handler returned value
     */
    public function send(RequestInterface $request)
    {
        //Wrap in an envelope?
        $response = $this->handle($request);

        return $response;
    }
}
