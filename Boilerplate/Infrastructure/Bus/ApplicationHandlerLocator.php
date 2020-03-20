<?php


namespace Boilerplate\Infrastructure\Bus;


use Illuminate\Container\Container;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class ApplicationHandlerLocator implements HandlersLocatorInterface
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var array|\callable[][]|\Symfony\Component\Messenger\Handler\HandlerDescriptor[][]
     */
    private array $handlers;

    /**
     * @param Container $container
     * @param HandlerDescriptor[][]|callable[][] $handlers
     */
    public function __construct(Container $container, array $handlers)
    {
        $this->container = $container;
        $this->handlers = $handlers;
    }

    /**
     * @param Envelope $envelope
     * @return iterable
     */
    public function getHandlers(Envelope $envelope): iterable
    {
        $seen = [];

        foreach (self::listTypes($envelope) as $type) {
            foreach ($this->handlers[$type] ?? [] as $handlerDescriptor) {
                if (is_string($handlerDescriptor)) {
                    $handlerDescriptor = $this->container->get($handlerDescriptor);
                }

                if (\is_callable($handlerDescriptor)) {
                    $handlerDescriptor = new HandlerDescriptor($handlerDescriptor);
                }

                if (!$this->shouldHandle($envelope, $handlerDescriptor)) {
                    continue;
                }

                $name = $handlerDescriptor->getName();
                if (\in_array($name, $seen)) {
                    continue;
                }

                $seen[] = $name;

                yield $handlerDescriptor;
            }
        }
    }

    /**
     * @param Envelope $envelope
     * @return array
     */
    public static function listTypes(Envelope $envelope): array
    {
        $class = \get_class($envelope->getMessage());

        return [$class => $class]
            + class_parents($class)
            + class_implements($class)
            + ['*' => '*'];
    }

    /**
     * @param Envelope $envelope
     * @param HandlerDescriptor $handlerDescriptor
     * @return bool
     *
     */
    private function shouldHandle(Envelope $envelope, HandlerDescriptor $handlerDescriptor)
    {
        if (null === $received = $envelope->last(ReceivedStamp::class)) {
            return true;
        }

        if (null === $expectedTransport = $handlerDescriptor->getOption('from_transport')) {
            return true;
        }

        return $received->getTransportName() === $expectedTransport;
    }
}