<?php

declare(strict_types=1);

namespace Api\Common\Bus\Abstracts;

use Api\Common\Bus\Interfaces\CommandInterface;

abstract class CommandAbstract extends RequestAbstract implements CommandInterface
{
}
