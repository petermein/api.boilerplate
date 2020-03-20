<?php


namespace Boilerplate\Infrastructure\Bus\Interfaces;


interface Handler
{
    //Don't implement the invoke, to keep return type non-generic
    //We can use this to later infer the return types for swagger
}