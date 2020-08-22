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
     * @OA\Property(format="int64", example=1)
     */
    public int $id;
}
