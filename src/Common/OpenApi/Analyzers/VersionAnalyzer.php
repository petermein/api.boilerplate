<?php


namespace Api\Common\OpenApi\Analyzers;


class VersionAnalyzer
{
    public function determineVersions()
    {
        //Check if key config is present
        $versions = config('openapi.versions', []);

        if (!is_array($versions) || count($versions) == 0) {
            //TODO: analysis on routes
        }

        return $versions;
    }
}
