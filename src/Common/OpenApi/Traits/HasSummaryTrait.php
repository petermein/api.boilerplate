<?php

namespace Api\Common\OpenApi\Traits;

trait HasSummaryTrait
{
    /**
     * @var string
     */
    public string $summary;

    public function getSummary(): string
    {
        return $this->summary;
    }
}
