<?php

declare(strict_types=1);


namespace Api\Common\Bus\Locators;

use Api\Common\Bus\Abstracts\RequestAbstract;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

/**
 * Class ApplicationSenderLocator
 * @package Api\Common\Bus\Locators
 */
final class ApplicationSenderLocator implements SendersLocatorInterface
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var iterable
     */
    private iterable $senders;

    /**
     * ApplicationSenderLocator constructor.
     * @param Container $container
     * @param iterable $senders
     */
    public function __construct(Container $container, iterable $senders)
    {
        $this->container = $container;
        $this->senders = $senders;
    }

    /**
     * @param Envelope $envelope
     * @return iterable
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getSenders(Envelope $envelope): iterable
    {
        $seen = [];

        //Find via enveloper
        /** @var RequestAbstract $query */
        $query = $envelope->getMessage();
        $senderDescriptions = $query->senders();

        //Found a handler description in the model
        foreach ($senderDescriptions ?? [] as $senderAlias) {
            if (!\in_array($senderAlias, $seen, true)) {
                if (!$this->container->make($senderAlias)) {
                    throw new RuntimeException(\Safe\sprintf(
                        'Invalid senders configuration: sender "%s" is not in the senders locator.',
                        $senderAlias
                    ));
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
                        throw new RuntimeException(\Safe\sprintf(
                            'Invalid senders configuration: sender "%s" is not in the senders locator.',
                            $senderAlias
                        ));
                    }

                    $seen[] = $senderAlias;
                    $sender = $this->container->get($senderAlias);
                    yield $senderAlias => $sender;
                }
            }
        }
    }
}
