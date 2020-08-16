<?php


namespace Api\Application\Example\Models;

/**
 * Class ExampleListDto
 *
 * @OA\Schema(
 *     title="Example list dto",
 *     description="Example list dto",
 * )
 */
class ExampleListDto
{
    /**
     * The unique identifier of a product in our catalog.
     *
     * @var integer
     * @OA\Property(format="int64", example=1)
     */
    public int $id;
}