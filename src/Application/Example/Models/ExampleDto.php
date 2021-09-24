<?php

declare(strict_types=1);

namespace Api\Application\Example\Models;

use Api\Common\DTO\DataTransferObject;
use Api\Domain\Enums\TestEnum;

final class ExampleDto extends DataTransferObject
{
    public int $id;

    public string $string;

    public float $double;

    public bool $boolean;

    public TestEnum $enum;
}
