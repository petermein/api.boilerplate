<?php

declare(strict_types=1);

namespace Api\Common\Bus\Locators;

use Api\Common\Bus\Interfaces\RequestInterface;
use Api\Common\Bus\Interfaces\ValidatorLocatorInterface;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\Handler\HandlersLocator;

/**
 * Class ApplicationValidatorLocator
 * @package Api\Common\Bus\Locators
 */
final class ApplicationValidatorLocator implements ValidatorLocatorInterface
{
    /**
     * @var Container
     */
    private Container $container;
    /**
     * @var iterable
     */
    private iterable $validators;

    /**
     * ApplicationValidatorLocator constructor.
     * @param Container $container
     * @param array $validators
     */
    public function __construct(Container $container, iterable $validators)
    {
        $this->container = $container;
        $this->validators = $validators;
    }

    /**
     * @param Envelope $envelope
     * @return iterable
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getValidators(Envelope $envelope): iterable
    {
        $seen = [];

        //Find via enveloper
        /** @var RequestInterface $query */
        $query = $envelope->getMessage();
        $validatorDescriptions = $query->validators();

        //Loop over found a validations description in the model, run over validators
        foreach ($validatorDescriptions ?? [] as $validatorDescription) {
            if (!\in_array($validatorDescription, $seen, true)) {
                if (!$this->container->has($validatorDescription)) {
                    throw new RuntimeException(
                        \Safe\sprintf(
                            'Invalid validator configuration: validator "%s" is not in the validator locator.',
                            $validatorDescription
                        )
                    );
                }

                $seen[] = $validatorDescription;
                $validator = $this->container->get($validatorDescription);
                yield $validatorDescription => $validator;
            }
        }

        //Run over registered validators
        foreach (HandlersLocator::listTypes($envelope) as $type) {
            foreach ($this->validators[$type] ?? [] as $validatorDescription) {
                if (!\in_array($validatorDescription, $seen, true)) {
                    if (!$this->container->make($validatorDescription)) {
                        throw new RuntimeException(
                            \Safe\sprintf(
                                'Invalid validator configuration: validator "%s" is not in the validator locator.',
                                $validatorDescription
                            )
                        );
                    }

                    $seen[] = $validatorDescription;
                    $validator = $this->container->get($validatorDescription);
                    yield $validatorDescription => $validator;
                }
            }
        }
    }
}
