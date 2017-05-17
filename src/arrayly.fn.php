<?php
declare(strict_types=1);

namespace Arrayly\fn;

function requireIterable(iterable $source):iterable {
    return $source;
};

function keys(array $source, bool $strict=true):array
{
    return array_keys($source, null, $strict);
}

function values(array $source):array
{
    return array_values($source);
}

function flip(array $source):array
{
    return array_flip($source);
}

function shuffle(array $source, int $times):array
{
    $sink = (array)$source;
    $i = 0;
    while ($i < $times) {
        \shuffle($sink);
    }

    return (array)$sink;
}

function count(array $source):int
{
    return \count($source);
}

function reverse(array $source, bool $preserveKeys):array
{
    return array_reverse($source, $preserveKeys);
}

function hasKey(array $source, $key):bool
{
    return array_key_exists($key, $source);
}

function firstOrDefault(array $source, $defaultValue){
    return firstOrElse($source, function () use ($defaultValue) {
        return $defaultValue;
    });
}

function firstOrNull(array $source)
{
    return firstOrDefault($source,null);
}

function firstOrElse(array $source, \Closure $defaultValueSupplier)
{
    foreach ($source as $item) {

        return $item;
    }

    return $defaultValueSupplier();
}

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

function onEach(array $source, \Closure $callback):void
{
    foreach ($source as $v) {
        $callback($v);
    }
}

function onEachIndexed(array $source, \Closure $callback):void
{
    foreach ($source as $k => $v) {
        $callback($k, $v);
    }
}

function filter(array $source, \Closure $predicate):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function filterIndexed(array $source,\Closure $predicate):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($k, $v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function map(array $source, \Closure $transform):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$k] = $transform($v);
    }

    return $sink;
}

function mapIndexed(array $source, \Closure $transform):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$k] = $transform($k, $v);
    }

    return $sink;
}

function mapKeys(array $source,\Closure $keySelector):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$keySelector($v)] = $v;
    }

    return $sink;
}

function mapKeysIndexed(array $source,\Closure $keySelector):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$keySelector($k, $v)] = $v;
    }

    return $sink;
}

function flatMap(array $source,\Closure $transform):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $transformedCollection = $transform($v);
        $transformedCollection = requireIterable($transformedCollection);
        foreach ($transformedCollection as $transformedItem) {
            $sink[] = $transformedItem;
        }
    }

    return $sink;
}

function flatMapIndexed(array $source, \Closure $transform):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $transformedCollection = $transform($k, $v);
        $transformedCollection = requireIterable($transformedCollection);
        foreach ($transformedCollection as $transformedItem) {
            $sink[] = $transformedItem;
        }
    }

    return $sink;
}

function groupBy(array $source, \Closure $keySelector):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $groupKey = $keySelector($v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }

    return $sink;
}

function groupByIndexed(array $source, \Closure $keySelector):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $groupKey = $keySelector($k, $v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }

    return $sink;
}

function reduce(array $source, $initialValue, \Closure $reducer)
{
    $accumulatedValue = $initialValue;
    foreach ($source as $k => $v) {
        $accumulatedValue = $reducer($accumulatedValue, $v);
    }

    return $accumulatedValue;
}

function reduceIndexed(array $source, $initialValue, \Closure $reducer)
{
    $accumulatedValue = $initialValue;
    foreach ($source as $k => $v) {
        $accumulatedValue = $reducer($accumulatedValue, $k, $v);
    }

    return $accumulatedValue;
}

function sortBy(array $source, \Closure $comparator, bool $descending):array
{
    $source = (array)$source;
    usort($source, $comparator);
    $sink = (array)$source;
    if ($descending) {
        $sink = array_reverse($sink);
    }

    return (array)$sink;
}

function take(array $source, int $amount):array
{
    $sink = [];
    $currentAmount = 0;
    foreach ($source as $k => $v) {
        if ($currentAmount >= $amount) {

            break;
        }
        $sink[$k] = $v;
        $currentAmount++;
    }

    return $sink;
}

function takeWhile(array $source, \Closure $predicate):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($v)) {
            $sink[$k] = $v;
        } else {

            break;
        }

    }

    return $sink;
}

function takeWhileIndexed(array $source,\Closure $predicate):array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($k, $v)) {
            $sink[$k] = $v;
        } else {

            break;
        }

    }

    return $sink;
}


function drop(array $source, int $amount):array
{
    $sink = [];
    $dropped = 0;
    foreach ($source as $k => $v) {
        if ($dropped < $amount) {
            $dropped++;
        } else {
            $sink[$k] = $v;
        }
    }

    return $sink;
}


function dropWhile(array $source, \Closure $predicate):array
{
    $sink = [];
    $failed = false;
    foreach ($source as $k => $v) {
        if (!$failed && !$predicate($v)) {
            $failed = true;
        }
        if ($failed) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function dropWhileIndexed(array $source,\Closure $predicate):array
{
    $sink = [];
    $failed = false;
    foreach ($source as $k => $v) {
        if (!$failed && !$predicate($k, $v)) {
            $failed = true;
        }
        if ($failed) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}



