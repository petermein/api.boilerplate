<?php


namespace Api\Application\Auth\Models;


use Api\Domain\Models\User as DomainUser;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Extended user model with depencies on illuminate contracts
 *
 * Class User
 * @package Api\Application\Auth\Models
 */
class User extends DomainUser implements Authenticatable
{
    public function getAuthIdentifierName(): string
    {
        return $this->name;
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        //Not implemented in stateless auth configuration
    }

    public function getRememberToken()
    {
        //Not implemented in stateless auth configuration
    }

    public function setRememberToken($value)
    {
        //Not implemented in stateless auth configuration
    }

    public function getRememberTokenName()
    {
        //Not implemented in stateless auth configuration
    }
}
