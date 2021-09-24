<?php

declare(strict_types=1);

namespace Api\Application\Example\Queries\GetAllQuery;

use Api\Common\Bus\Abstracts\ValidatorAbstract;

/**
 * Class GetAllExamplesQueryValidator
 * @package Api\Application\Example\Queries\GetAllQuery
 */
final class GetAllExamplesQueryValidator extends ValidatorAbstract
{
    public function rules(): array
    {
        return [
            'id' => 'required'
        ];
    }
}
