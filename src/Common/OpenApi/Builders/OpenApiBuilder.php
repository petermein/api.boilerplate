<?php

namespace Api\Common\OpenApi\Builders;

use cebe\openapi\spec\OpenApi;

class OpenApiBuilder
{
    /**
     * @var ServerBuilder
     */
    private ServerBuilder $serverBuilder;
    /**
     * @var InfoBuilder
     */
    private InfoBuilder $infoBuilder;
    /**
     * @var PathBuilder
     */
    private PathBuilder $pathBuilder;

    /**
     * OpenApiBuilder constructor.
     * @param InfoBuilder $infoBuilder
     * @param ServerBuilder $serverBuilder
     * @param PathBuilder $pathBuilder
     */
    public function __construct(InfoBuilder $infoBuilder, ServerBuilder $serverBuilder, PathBuilder $pathBuilder)
    {
        $this->infoBuilder = $infoBuilder;
        $this->serverBuilder = $serverBuilder;
        $this->pathBuilder = $pathBuilder;
    }

    public function build($version)
    {
        $openApi = new OpenApi([
            'openapi' => config('openapi.openapi.version'), //Open API version
            'info' => $this->infoBuilder->generateInfo($version),
            'servers' => $this->serverBuilder->generateGlobalServerlist(),
            'paths' => $this->pathBuilder->generatePaths($version),
//            'components' => Components::class,
//            'security' => [SecurityRequirement::class],
//            'tags' => [Tag::class],
//            'externalDocs' => ExternalDocumentation::class,
        ]);

        return $openApi;
    }
}
