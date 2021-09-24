<?php

namespace Api\Application\Auth\Queries\Me;

use Api\Common\Bus\Abstracts\QueryAbstract;

class MeQuery extends QueryAbstract
{
    protected bool $providesValidators = false;
}
