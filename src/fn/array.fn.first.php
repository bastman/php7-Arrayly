<?php
declare(strict_types=1);

namespace Arrayly\fn;

function firstOrDefault(array $source, $defaultValue)
{
    return firstOrElse($source, function () use ($defaultValue) {
        return $defaultValue;
    });
}

function firstOrNull(array $source)
{
    return firstOrDefault($source, null);
}

function firstOrElse(array $source, \Closure $defaultValueSupplier)
{
    foreach ($source as $item) {

        return $item;
    }

    return $defaultValueSupplier();
}