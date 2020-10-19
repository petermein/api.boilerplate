<?php


namespace Api\Common\OpenApi\Traits;


trait HasDescriptionTrait
{
    /**
     * @var string
     */
    public string $description;

    public function getDescription(): string
    {
        return $this->description;
    }
}
