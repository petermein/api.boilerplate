<?php

namespace Api\Application\Auth\Models;

use Api\Common\DTO\DataTransferObject;

/**
 * Extended user model with depencies on illuminate contracts
 *
 * Class User
 * @package Api\Application\Auth\Models
 */
final class UserDto extends DataTransferObject
{
    public int $id;

    public string $name;

    public array $scope;
}
