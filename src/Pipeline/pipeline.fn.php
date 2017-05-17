<?php
declare(strict_types=1);

namespace Arrayly\Pipeline\fn;

use Arrayly\Sequence\generators as generate;

function genReduce(iterable $iterable, $initialValue, \Closure $reducer): \Generator
{
    $accumulated = $initialValue;
    foreach ($iterable as $k => $v) {
        $accumulated = $reducer($accumulated, $v);
    }

    yield $accumulated;
}

function reduce($initialValue, \Closure $reducer): \Closure
{
    return function (iterable $iterable) use ($initialValue, $reducer) {
        return genReduce($iterable, $initialValue, $reducer);
    };
}

function iterate(): \Closure
{
    return function (iterable $iterable) {
        return generate\iterate($iterable);
    };
}

;

function pipeTo(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\pipeTo($iterable, $transform);
    };
}

function keys(): \Closure
{
    return function (iterable $iterable) {
        return generate\keys($iterable);
    };
}

;

function values(): \Closure
{
    return function (iterable $iterable) {
        return generate\values($iterable);
    };
}

function flip(): \Closure
{
    return function (iterable $iterable) {
        return generate\flip($iterable);
    };
}

function reverse(bool $preserveKeys): \Closure
{
    return function (iterable $iterable) use ($preserveKeys) {
        return generate\reverse($iterable, $preserveKeys);
    };
}

function onEach(\Closure $callback): \Closure
{
    return function (iterable $iterable) use ($callback) {
        return generate\onEach($iterable, $callback);
    };
}

function map(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\map($iterable, $transform);
    };
}

function mapKeys(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\mapKeys($iterable, $keySelector);
    };
}

function mapKeysIndexed(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\mapKeysIndexed($iterable, $keySelector);
    };
}

function filter(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\filter($iterable, $predicate);
    };
}

function flatMap(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\flatMap($iterable, $transform);
    };
}

function groupBy(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\groupBy($iterable, $keySelector);
    };
}

function take(int $amount): \Closure
{
    return function (iterable $iterable) use ($amount) {
        return generate\take($iterable, $amount);
    };
}

function drop(int $amount): \Closure
{
    return function (iterable $iterable) use ($amount) {
        return generate\drop($iterable, $amount);
    };
}

function takeWhile(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\takeWhile($iterable, $predicate);
    };
}

function dropWhile(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\dropWhile($iterable, $predicate);
    };
}

function sortBy(\Closure $comparator, bool $descending): \Closure
{
    return function (iterable $iterable) use ($comparator, $descending) {
        return generate\sortBy($iterable, $comparator, $descending);
    };
}