<?php

declare(strict_types = 1);



namespace Api\Infrastructure\Bus\Interfaces;


interface HandlerInterface
{
    //Don't implement the invoke, to keep return type non-generic
    //We can use this to later infer the return types for swagger
}