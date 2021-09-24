<?php

declare(strict_types=1);

namespace Api\Presentation\Api\REST\Example\v1;

use Api\Application\Example\Models\ExampleListDto;
use Api\Application\Example\Queries\GetAllQuery\GetAllExamplesQuery;
use Api\Presentation\Api\REST\RESTController;
use Psr\Log\LoggerInterface;

/**
 * Class ExampleController
 * @package Api\Presentation\Api\REST\Example
 */
final class ExampleController extends RESTController
{
    public string $summary = 'Examples';

    public string $description = 'Examples description';

    public function getAll(GetAllExamplesQuery $request, LoggerInterface $logger): ExampleListDto
    {
        return $this->send($request);
    }

    public function post(GetAllExamplesQuery $request, LoggerInterface $logger): ExampleListDto
    {
        return $this->send($request);
    }
}
