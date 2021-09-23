<?php

declare(strict_types=1);


if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path(string $path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

//Only is use
if (!function_exists('resolve')) {
    function resolve($name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}
