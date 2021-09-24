<?php

declare(strict_types=1);

namespace Api\Application\Example\Queries\GetAllQuery;

use Api\Common\Bus\Abstracts\ValidatorAbstract;

/**
 * Class GetAllExamplesQueryValidatorBound
 * @package Api\Application\Example\Queries\GetAllQuery
 */
final class GetAllExamplesQueryValidatorBound extends ValidatorAbstract
{
    public function rules(): array
    {
        return [
            'id' => 'min:1'
        ];
    }
}
