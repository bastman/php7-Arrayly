<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

use Arrayly\Util\internals as utils;

function flatMap(array $source, \Closure $transform): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $transformedCollection = $transform($v);
        $transformedCollection = utils\requireIterable($transformedCollection);
        foreach ($transformedCollection as $transformedItem) {
            $sink[] = $transformedItem;
        }
    }

    return $sink;
}

function flatMapIndexed(array $source, \Closure $transform): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $transformedCollection = $transform($k, $v);
        $transformedCollection = utils\requireIterable($transformedCollection);
        foreach ($transformedCollection as $transformedItem) {
            $sink[] = $transformedItem;
        }
    }

    return $sink;
}
