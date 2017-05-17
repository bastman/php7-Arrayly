<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function flatMap(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        $transformedCollection = requireIterable($transform($v));
        foreach ($transformedCollection as $transformedItem) {
            yield $transformedItem;
        }
    }
}

function flatMapIndexed(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        $transformedCollection = requireIterable($transform($k, $v));
        foreach ($transformedCollection as $transformedItem) {
            yield $transformedItem;
        }
    }
}


