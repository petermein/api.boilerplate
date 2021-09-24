<?php

declare(strict_types=1);

namespace Api\Application\Auth\Queries\Me;

use Api\Application\Auth\Models\User;
use Api\Application\Auth\Models\UserDto;
use Api\Common\Bus\Interfaces\HandlerInterface;
use Illuminate\Contracts\Auth\Guard;

/**
 * Class GetAllExamplesQueryHandler
 * @package Api\Application\Example\Queries\GetAllQuery
 */
final class MeQueryHandler implements HandlerInterface
{
    protected Guard $auth;

    /**
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param MeQuery $getAllQuery
     * @return UserDto
     */
    public function __invoke(MeQuery $getAllQuery): UserDto
    {
        /** @var User $user */
        $user = $this->auth->user();

        return new UserDto($user->toArray());
    }
}
