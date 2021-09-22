<?php


namespace Api\Common\OpenApi\Traits;


trait HasDescriptionTrait
{
    /**
     * @var string
     */
    protected string $description;

    public function getDescription(): string
    {
        return $this->description;
    }
}
