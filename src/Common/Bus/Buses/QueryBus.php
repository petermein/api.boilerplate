<?php

declare(strict_types=1);


namespace Api\Common\Bus\Buses;

use Api\Common\Bus\Interfaces\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class QueryBus
 * @package Api\Common\Bus\Buses
 */
final class QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param QueryInterface $query
     *
     * @return mixed The handler returned value
     */
    public function query(QueryInterface $query)
    {
        //Wrap in an envelope?
        $response = $this->handle($query);

        return $response;
    }
}
