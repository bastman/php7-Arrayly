<?php
declare(strict_types=1);

namespace Arrayly\fn;


function findOrNull(array $source, \Closure $predicate)
{
    return findOrDefault($source, $predicate, null);
}

function findOrDefault(array $source, \Closure $predicate, $defaultValue)
{
    return findOrElse($source, $predicate, function () use ($defaultValue) {
        return $defaultValue;
    });
}

function findOrElse(array $source, \Closure $predicate, \Closure $defaultValueSupplier)
{
    foreach ($source as $k => $v) {
        if ($predicate($v)) {

            return $v;
        }
    }

    return $defaultValueSupplier();
}


function findIndexedOrNull(array $source, \Closure $predicate)
{
    return findIndexedOrDefault($source, $predicate, null);
}

function findIndexedOrDefault(array $source, \Closure $predicate, $defaultValue)
{
    return findIndexedOrElse($source, $predicate, function () use ($defaultValue) {
        return $defaultValue;
    });
}

function findIndexedOrElse(array $source, \Closure $predicate, \Closure $defaultValueSupplier)
{
    foreach ($source as $k => $v) {
        if ($predicate($k, $v) === true) {

            return $v;
        }
    }

    return $defaultValueSupplier();
}