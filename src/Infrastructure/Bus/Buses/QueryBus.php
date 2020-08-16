<?php


namespace Api\Infrastructure\Bus\Buses;


use Api\Common\Timing\Traits\HasTiming;
use Api\Infrastructure\Bus\Interfaces\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus
{
    use HandleTrait;
    use HasTiming;

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
        $this->start('Bus handling');

        //Wrap in an envelope?
        $response = $this->handle($query);

        $this->stop('Bus handling');

        return $response;
    }
}