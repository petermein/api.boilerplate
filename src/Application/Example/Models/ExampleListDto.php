<?php

declare(strict_types=1);

namespace Api\Application\Example\Models;

use Api\Common\DTO\DataTransferObject;

/**
 * Class ExampleListDto
 *
 * @OA\Schema(
 *     title="Example list dto",
 *     description="Example list dto",
 * )
 */
final class ExampleListDto extends DataTransferObject
{
    /**
     * The unique identifier of a product in our catalog.
     *
     * @var integer
     */
    public int $id;
    /**
     * @var string
     */
    public string $string;
    /**
     * @var float
     */
    public float $double;
    /**
     * @var bool
     */
    public bool $boolean;
    /**
     * @var ExampleListDto[]
     */
    public array $array;
}
