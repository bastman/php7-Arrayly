<?php
declare(strict_types=1);

namespace Arrayly\fn;

function getOrElse(array $source, $key, \Closure $defaultValueSupplier)
{
    if (array_key_exists($key, $source)) {

        return $source[$key];
    }

    return $defaultValueSupplier();
}

function getOrNull(array $source, $key)
{
    return getOrDefault($source, $key, null);
}

function getOrDefault(array $source, $key, $defaultValue)
{
    return getOrElse($source, $key, function () use ($defaultValue) {
        return $defaultValue;
    });
}