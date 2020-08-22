<?php

declare(strict_types=1);


if (!function_exists('set_object_vars')) {
    function set_object_vars(object $object, array $vars)
    {
        $has = get_object_vars($object);
        foreach ($has as $name => $oldValue) {
            $object->$name = array_key_exists($name, $vars) ? $vars[$name] : null;
        }
    }
}
