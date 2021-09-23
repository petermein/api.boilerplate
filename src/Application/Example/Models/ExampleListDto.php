<?php

declare(strict_types=1);

namespace Api\Application\Example\Models;

use Api\Common\DTO\DataTransferObject;

final class ExampleListDto extends DataTransferObject
{
    /**
     * The unique identifier of a product in our catalog.
     *
     * @var integer
     */
    public int $id;


    /**
     * @var array|ExampleDto[]
     */
    public array $examples;
}
