<?php

declare(strict_types=1);


namespace Api\Application\System\Validators;

use Api\Common\Bus\Abstracts\ValidatorAbstract;

final class GlobalValidator extends ValidatorAbstract
{
    public function rules(): array
    {
        return [
            'id' => 'min:5'
        ];
    }
}
