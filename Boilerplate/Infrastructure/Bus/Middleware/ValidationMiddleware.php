<?php


namespace Boilerplate\Infrastructure\Bus\Middleware;


use Boilerplate\Infrastructure\Bus\Interfaces\Query;
use Boilerplate\Infrastructure\Bus\Interfaces\Validator;
use Illuminate\Container\Container;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class ValidationMiddleware implements MiddlewareInterface
{
    /**
     * @var Container
     */
    private Container $container;
    /**
     * @var Factory
     */
    private Factory $factory;
    /**
     * @var array
     */
    private array $validators;

    /**
     * ValidationMiddleware constructor.
     * @param Container $container
     * @param array $validators
     */
    public function __construct(Container $container, array $validators)
    {
        $this->container = $container;
        $this->factory = $this->container->get(Factory::class);
        $this->validators = $validators;
    }


    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        //TODO: create validator locator

        foreach (HandlersLocator::listTypes($envelope) as $type) {
            foreach ($this->validators[$type] ?? [] as $validatorClass) {
                /** @var Validator $validatorRules */
                $validatorRules = $this->container->get($validatorClass);

                //CHeck if there is validator for this query
                /** @var Query $query */
                $query = $envelope->getMessage();

                /** @var \Illuminate\Validation\Validator $validator */
                if (($validator = $this->factory->make(
                    $query->getData(),
                    $validatorRules->rules(),
                    $validatorRules->messages(),
                    $validatorRules->customAttributes()))->fails()
                ) {
                    throw new ValidationException($validator);
                }
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}