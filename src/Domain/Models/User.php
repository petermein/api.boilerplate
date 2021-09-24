<?php

declare(strict_types=1);

namespace Api\Domain\Models;

/**
 * Class User
 * @package Api\Domain\Models
 */
class User
{
    public string $id;

    public string $name;

    public array $scope;

    /**
     * User constructor.
     *
     * @param string $id identifier of user
     * @param string $name name of user
     * @param array $scope list of scopes as value
     */
    public function __construct(string $id, string $name, array $scope)
    {
        $this->id = $id;
        $this->name = $name;
        $this->scope = $scope;
    }

    /**
     * Determine is user has a specific scope
     *
     * @param $scope
     * @return bool
     */
    public function hasScope($scope): bool
    {
        return isset(\Safe\array_flip($this->scope)[$scope]);
    }
}//end class
