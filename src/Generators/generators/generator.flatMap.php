<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;
use Arrayly\Util\internals as utils;

function flatMap(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        $transformedCollection = utils\requireIterable($transform($v));
        foreach ($transformedCollection as $transformedItem) {
            yield $transformedItem;
        }
    }
}

function flatMapIndexed(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        $transformedCollection = utils\requireIterable($transform($k, $v));
        foreach ($transformedCollection as $transformedItem) {
            yield $transformedItem;
        }
    }
}


