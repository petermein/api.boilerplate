<?php

namespace Api\Common\OpenApi\Commands;

use Api\Common\OpenApi\Analyzers\VersionAnalyzer;
use Api\Common\OpenApi\Builders\OpenApiBuilder;
use cebe\openapi\Writer;
use Illuminate\Console\Command;
use Laravel\Lumen\Application;

class GenerateOpenApiCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'openapi:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a OpenApi json';
    /**
     * @var Writer
     */
    private Writer $specWriter;
    /**
     * @var VersionAnalyzer
     */
    private VersionAnalyzer $versionAnalyzer;

    /**
     * GenerateOpenApiCommand constructor.
     * @param Application $application
     * @param VersionAnalyzer $versionAnalyzer
     * @param Writer $specWriter
     */
    public function __construct(Application $application, VersionAnalyzer $versionAnalyzer, Writer $specWriter)
    {
        $application->configure('openapi');
        $this->versionAnalyzer = $versionAnalyzer;
        $this->specWriter = $specWriter;

        parent::__construct();
    }

    public function handle(OpenApiBuilder $openApiBuilder)
    {

        //Check available version in route

        //Build open api spec for each version

        $versions = $this->versionAnalyzer->determineVersions();

        foreach ($versions as $version) {
            $spec = $openApiBuilder->build($version);

            if (!$spec->validate()) {
                foreach ($spec->getErrors() as $error) {
                    $this->output->error($error);
                }

                return;
            }

            $path = storage_path(\Safe\sprintf('docs/openapi.%s.json', $version));
            $this->specWriter::writeToJsonFile($spec, $path);

            $this->info('Generated version file:' . $path);
        }
    }
}
