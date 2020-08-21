<?php

declare(strict_types = 1);



namespace Api\Infrastructure\Bus\Locators;


use Api\Infrastructure\Bus\Abstracts\QueryAbstract;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

class ApplicationSenderLocator implements SendersLocatorInterface
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var array
     */
    private array $senders;

    /**
     * @param Container $container
     * @param HandlerDescriptor[][]|callable[][] $handlers
     */
    public function __construct(Container $container, array $senders)
    {
        $this->container = $container;
        $this->senders = $senders;
    }

    /**
     * @param Envelope $envelope
     * @return iterable
     */
    public function getSenders(Envelope $envelope): iterable
    {
        $seen = [];

        //Find via enveloper
        /** @var QueryAbstract $query */
        $query = $envelope->getMessage();
        $senderDescriptions = $query->senders();

        //Found a handler description in the model
        foreach ($senderDescriptions ?? [] as $senderAlias) {
            if (!\in_array($senderAlias, $seen, true)) {

                if (!$this->container->make($senderAlias)) {
                    throw new RuntimeException(sprintf('Invalid senders configuration: sender "%s" is not in the senders locator.',
                        $senderAlias));
                }

                $seen[] = $senderAlias;
                $sender = $this->container->get($senderAlias);
                yield $senderAlias => $sender;
            }
        }

        //Find via binding
        foreach (HandlersLocator::listTypes($envelope) as $type) {
            foreach ($this->senders[$type] ?? [] as $senderAlias) {

                if (!\in_array($senderAlias, $seen, true)) {

                    if (!$this->container->make($senderAlias)) {
                        throw new RuntimeException(sprintf('Invalid senders configuration: sender "%s" is not in the senders locator.',
                            $senderAlias));
                    }

                    $seen[] = $senderAlias;
                    $sender = $this->container->get($senderAlias);
                    yield $senderAlias => $sender;
                }
            }
        }
    }
}