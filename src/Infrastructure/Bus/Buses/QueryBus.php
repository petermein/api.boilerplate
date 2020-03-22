<?php


namespace Api\Infrastructure\Bus\Buses;


use Api\Infrastructure\Bus\Interfaces\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus
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

        return $this->handle($query);
    }
}