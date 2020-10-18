<?php


namespace Api\Common\OpenApi\Commands;


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
    protected $name = 'OpenApi:generate';

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
     * GenerateOpenApiCommand constructor.
     * @param Application $application
     * @param Writer $specWriter
     */
    public function __construct(Application $application, Writer $specWriter)
    {
        $application->configure('openapi');
        $this->specWriter = $specWriter;

        parent::__construct();
    }

    public function handle(OpenApiBuilder $openApiBuilder)
    {

        //Check available version in route

        //Build open api spec for each version

        $version = 'v1';

        $spec = $openApiBuilder->build($version);

        if (!$spec->validate()) {
            foreach ($spec->getErrors() as $error) {
                $this->output->error($error);
            }

            return;
        }

        $path = storage_path(sprintf('docs/openapi.%s.json', $version));
        $this->specWriter::writeToJsonFile($spec, $path);

        $this->info('Generated version file:' . $path);
    }
}
