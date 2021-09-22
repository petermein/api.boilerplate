<?php


namespace Api\Presentation\Console\Commands;

use Api\Common\Bus\Interfaces\RequestInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ReflectionClass;
use Symfony\Component\Console\Input\InputOption;

/**
 * Re-implemenation of the route command from laravel
 *
 * Class RoutesCommand
 * @package Api\Presentation\Console\Commands
 */
class OpenApiListCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'openapi:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start of swagger command';

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = array('Verb', 'Path', 'Controller', 'Action', 'Request', 'Response');

    /**
     * The columns to display when using the "compact" flag.
     *
     * @var array
     */
    protected $compactColumns = ['verb', 'path', 'controller', 'action'];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->displayRoutes($this->getRoutes());
    }

    /**
     * Display the route information on the console.
     *
     * @param array $routes
     * @return void
     */
    protected function displayRoutes(array $routes)
    {
        if (empty($routes)) {
            return $this->error("Your application doesn't have any routes.");
        }

        $this->table($this->getHeaders(), $routes);
    }

    /**
     * Get the table headers for the visible columns.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return Arr::only($this->headers, array_keys($this->getColumns()));
    }

    /**
     * Get the column names to show (lowercase table headers).
     *
     * @return array
     */
    protected function getColumns()
    {
        $availableColumns = array_map('lcfirst', $this->headers);

        if ($this->option('compact')) {
            return array_intersect($availableColumns, $this->compactColumns);
        }

        if ($columns = $this->option('columns')) {
            return array_intersect($availableColumns, array_map('lcfirst', $columns));
        }

        return $availableColumns;
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes()
    {
        global $app;

        $routeCollection = property_exists($app, 'router') ? $app->router->getRoutes() : $app->getRoutes();
        $rows = array();
        foreach ($routeCollection as $route) {
            $controller = $this->getController($route['action']);

            //Start reflection on controller
            list($request, $response) = $this->reflectOnAction($route['action']);

            if ($request == false || $response == false) {
                //Don't inlcude non request abstract routes
                continue;
            }

            //Create base swagger info


            $rows[] = [
                'verb' => $route['method'],
                'path' => $route['uri'],
                'controller' => $controller,
                'action' => $this->getAction($route['action']),
                'request' => $request,
                'response' => $response
            ];
        }

        return $this->pluckColumns($rows);
    }

    /**
     * @param array $action
     * @return mixed|string
     */
    protected function getController(array $action)
    {
        if (empty($action['uses'])) {
            return 'None';
        }

        return current(explode("@", $action['uses']));
    }

    public function reflectOnAction(array $action): array
    {
        $class = $this->getController($action);
        $method = $this->getAction($action);

        //Don't check closures
        if ($method == 'Closure') {
            return [false, false];
        }

        $reflection = new \ReflectionMethod($class, $method);

        //TODO: move to functional magic with colleciton
        $params = $reflection->getParameters();
        $requests = new Collection();

        foreach ($params as $param) {
            $reflectionClass = $param->getType() && !$param->getType()->isBuiltin()
                ? new ReflectionClass($param->getType()->getName())
                : null;
            if ($reflectionClass === null) {
                continue;
            }

            $interfaces = $reflectionClass->getInterfaces();

            if (isset($interfaces[RequestInterface::class])) {
                $requests->add($reflectionClass);
            }
        }

        //Validate with atleast one request object
        if ($requests->isEmpty()) {
            return [false, false];
        }

        if ($requests->count() > 1) {
            throw new \Exception('Multiple request params is not yet supported');
        }

        $returnType = $reflection->getReturnType();
        $returnClass = $returnType->getName();

        return [$requests->first()->getName(), $returnClass];
    }

    /**
     * @param array $action
     * @return string
     */
    protected function getAction(array $action)
    {
        if (!empty($action['uses'])) {
            $data = $action['uses'];
            if (($pos = strpos($data, "@")) !== false) {
                return \Safe\substr($data, $pos + 1);
            } else {
                return "METHOD NOT FOUND";
            }
        } else {
            return 'Closure';
        }
    }

    /**
     * Remove unnecessary columns from the routes.
     *
     * @param array $routes
     * @return array
     */
    protected function pluckColumns(array $routes)
    {
        return array_map(function ($route) {
            return Arr::only($route, $this->getColumns());
        }, $routes);
    }

    /**
     * @param array $action
     * @return string
     */
    protected function getNamedRoute(array $action)
    {
        return (!isset($action['as'])) ? "" : $action['as'];
    }

    /**
     * @param array $action
     * @return string
     */
    protected function getMiddleware(array $action)
    {
        return (isset($action['middleware']))
            ? (is_array($action['middleware']))
                ? join(", ", $action['middleware'])
                : $action['middleware'] : '';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            [
                'columns',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Columns to include in the route table (' . implode(', ', $this->headers) . ')'
            ],

            [
                'compact',
                'c',
                InputOption::VALUE_NONE,
                'Only show verb, path, controller and action columns'
            ]
        ];
    }
}
