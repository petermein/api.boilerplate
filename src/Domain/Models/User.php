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

    public ?string $name;

    public array $scopes;

    public ?int $age;

    /**
     * User constructor.
     *
     * @param string $id identifier of user
     * @param string $name name of user
     * @param array $scopes list of scopes as value
     */
    public function __construct(string $id, string $name, array $scopes)
    {
        $this->id = $id;
        $this->name = $name;
        $this->scopes = $scopes;
    }

    /**
     * Determine is user has a specific scope
     *
     * @param $scope
     * @return bool
     */
    public function hasScope($scope): bool
    {
        return isset(\Safe\array_flip($this->scopes)[$scope]);
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }
}//end class
