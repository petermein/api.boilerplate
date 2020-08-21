<?php

declare(strict_types = 1);


if(!function_exists('set_object_vars')){
    function set_object_vars($object, array $vars) {
        $has = get_object_vars($object);
        foreach ($has as $name => $oldValue) {
            $object->$name = isset($vars[$name]) ? $vars[$name] : NULL;
        }
    }
}