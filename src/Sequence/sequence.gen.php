<?php
declare(strict_types=1);

namespace Arrayly\Sequence\gen;

function requireIterable(iterable $iterable)
{
    return $iterable;
}

;

function iterate(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k => $v;
    }
}

function pipeTo(iterable $iterable, \Closure $transform): \Generator
{
    yield from requireIterable($transform($iterable));
}

function keys(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k;
    }
}

function values(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $v;
    }
}

function flip(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $v => $k;
    }
}

function reverse(iterable $iterable, bool $preserveKeys): \Generator
{
    $source = [];
    foreach ($iterable as $k => $v) {
        $source[$k] = $v;
    }
    $sink = array_reverse($source, $preserveKeys);
    foreach ($sink as $k => $v) {
        yield $k => $v;
    }
}

function onEach(iterable $iterable, \Closure $callback): \Generator
{
    foreach ($iterable as $k => $v) {
        $callback($v);
        yield $k => $v;
    }
}

function map(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k => $transform($v);
    }
}

function mapKeys(iterable $iterable, \Closure $keySelector): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $keySelector($v) => $v;
    }
}

function mapKeysIndexed(iterable $iterable, \Closure $keySelector): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $keySelector($k, $v) => $v;
    }
}

function filter(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if ($predicate($v)) {
            yield $k => $v;
        }
    }
}

function flatMap(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        $transformedCollection = requireIterable($transform($v));
        foreach ($transformedCollection as $transformedItem) {
            yield $transformedItem;
        }
    }
}

function groupBy(iterable $iterable, \Closure $keySelector): \Generator
{
    $sink = [];
    foreach ($iterable as $k => $v) {
        $groupKey = $keySelector($v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }
    yield from $sink;
}


function take(iterable $iterable, int $amount): \Generator
{
    foreach ($iterable as $k => $v) {
        $currentAmount = 0;
        if ($currentAmount >= $amount) {
            break;
        }
        yield $k => $v;

    }
}

function drop(iterable $iterable, int $amount): \Generator
{
    $dropped = 0;
    foreach ($iterable as $k => $v) {
        if ($dropped < $amount) {
            $dropped++;
        } else {
            yield $k => $v;
        }
    }
}

function takeWhile(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if (!$predicate($v)) {

            return; // break???
        }
        yield $k => $v;
    }
}

function dropWhile(iterable $iterable, \Closure $predicate): \Generator
{
    $failed = false;
    foreach ($iterable as $key => $value) {
        if (!$failed && !$predicate($value)) {
            $failed = true;
        }
        if ($failed) {
            yield $key => $value;
        }
    }
}

function sortBy(iterable $iterable, \Closure $comparator, bool $descending): \Generator
{
    $source = [];
    foreach ($iterable as $k => $v) {
        $source[$k] = $v;
    }
    usort($source, $comparator);
    $sink = (array)$source;
    if ($descending) {
        $sink = array_reverse($sink);
    }
    yield from $sink;
}