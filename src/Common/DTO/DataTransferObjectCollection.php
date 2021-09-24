<?php

declare(strict_types=1);

namespace Api\Common\DTO;

use Api\Common\OpenApi\Contracts\HasDescription;
use Api\Common\OpenApi\Contracts\HasStatusCode;
use Api\Common\OpenApi\Traits\HasDescriptionTrait;
use Api\Common\OpenApi\Traits\HasStatusCodeTrait;

abstract class DataTransferObjectCollection extends \Spatie\DataTransferObject\DataTransferObjectCollection implements HasDescription, HasStatusCode
{
    use HasDescriptionTrait;
    use HasStatusCodeTrait;
}
