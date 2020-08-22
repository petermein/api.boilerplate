<?php

declare(strict_types=1);

namespace Api\Application\Example\Queries\GetAllQuery;

use Api\Application\System\Interfaces\HasRequestMapping;
use Api\Common\Bus\Abstracts\QueryAbstract;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetAllExamplesQuery
 * @package Api\Application\Example\Queries\GetAllQuery
 */
final class GetAllExamplesQuery extends QueryAbstract implements HasRequestMapping
{
    /**
     * @var int|null
     */
    public ?int $id = null;

    /**
     * @var null|string
     */
    public ?string $idPrefixed;

    public function requestMapping(Request $request): HasRequestMapping
    {
        $this->idPrefixed = 'prefix_' . $request->get('id');

        return $this;
    }
}
