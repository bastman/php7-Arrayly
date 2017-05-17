<?php
declare(strict_types=1);

namespace Arrayly\fn;

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
