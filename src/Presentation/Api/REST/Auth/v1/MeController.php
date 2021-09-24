<?php

namespace Api\Presentation\Api\REST\Auth\v1;


use Api\Application\Auth\Models\UserDto;
use Api\Application\Auth\Queries\Me\MeQuery;
use Api\Presentation\Api\REST\RESTController;
use Psr\Log\LoggerInterface;

/**
 * Class MeController
 * @package Api\Presentation\Api\REST\Auth
 */
final class MeController extends RESTController
{
    public string $summary = 'Me';

    public string $description = 'Me description';

    public function getAll(MeQuery $request, LoggerInterface $logger): UserDto
    {
        return $this->send($request);
    }
}
