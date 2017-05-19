<?php
declare(strict_types=1);

namespace Arrayly\Sequence\partials;
use Arrayly\Sequence\generators as generate;

function onEach(\Closure $callback): \Closure
{
    return function (iterable $iterable) use ($callback) {
        return generate\onEach($iterable, $callback);
    };
}

function onEachIndexed(\Closure $callback): \Closure
{
    return function (iterable $iterable) use ($callback) {
        return generate\onEachIndexed($iterable, $callback);
    };
}