<?php


namespace Boilerplate\Infrastructure\Bus;


use Boilerplate\Infrastructure\Bus\Interfaces\Query;
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
     * @param Query $query
     *
     * @return mixed The handler returned value
     */
    public function query(Query $query)
    {
        //Wrap in an envelope?

        return $this->handle($query);
    }
}