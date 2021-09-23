<?php

declare(strict_types=1);

namespace Api\Common\Bus\Interfaces;

use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

/**
 * Interface ValidatorInterface
 * @package Api\Common\Bus\Interfaces
 */
interface ListernerInterface extends SenderInterface
{
}
