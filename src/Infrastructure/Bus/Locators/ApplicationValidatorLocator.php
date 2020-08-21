<?php

declare(strict_types = 1);



namespace Api\Infrastructure\Bus\Locators;


use Api\Infrastructure\Bus\Interfaces\QueryInterface;
use Api\Infrastructure\Bus\Interfaces\ValidatorLocatorInterface;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\RuntimeException;
use Symfony\Component\Messenger\Handler\HandlersLocator;

class ApplicationValidatorLocator implements ValidatorLocatorInterface
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var array
     */
    private $validators;

    /**
     * ApplicationValidatorLocator constructor.
     * @param Container $container
     */
    public function __construct(Container $container, array $validators)
    {
        $this->container = $container;
        //TODO implement
        $this->validators = $validators;
    }

    public function getValidators(Envelope $envelope): iterable
    {
        $seen = [];

        //Find via enveloper
        /** @var QueryInterface $query */
        $query = $envelope->getMessage();
        $validatorDescriptions = $query->validators();

        //Loop over found a validations description in the model, run over validators
        foreach ($validatorDescriptions ?? [] as $validatorDescription) {
            if (!\in_array($validatorDescription, $seen, true)) {

                if (!$this->container->make($validatorDescription)) {
                    throw new RuntimeException(sprintf('Invalid validator configuration: validator "%s" is not in the validator locator.',
                        $validatorDescription));
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
                        throw new RuntimeException(sprintf('Invalid validator configuration: validator "%s" is not in the validator locator.',
                            $validatorDescription));
                    }

                    $seen[] = $validatorDescription;
                    $validator = $this->container->get($validatorDescription);
                    yield $validatorDescription => $validator;
                }
            }
        }
    }
}