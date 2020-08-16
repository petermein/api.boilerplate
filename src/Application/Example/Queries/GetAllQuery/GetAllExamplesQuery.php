<?php
declare(strict_types=1);


namespace Api\Application\Example\Queries\GetAllQuery;


use Api\Application\System\Interfaces\HasRequestMapping;
use Api\Infrastructure\Bus\Abstracts\QueryAbstract;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GetAllExamplesQuery
 *
 * @package Api\Application\Example\Queries\GetAllQuery
 */
class GetAllExamplesQuery extends QueryAbstract implements HasRequestMapping
{

    public ?int $id = null;

    public $idPrefixed;

    public function requestMapping(Request $request): HasRequestMapping
    {
        $this->idPrefixed = 'prefix_' . $request->get('id');

        return $this;
    }
}