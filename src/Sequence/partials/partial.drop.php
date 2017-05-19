<?php
declare(strict_types=1);

namespace Arrayly\Sequence\partials;
use Arrayly\Sequence\generators as generate;

function drop(int $amount): \Closure
{
    return function (iterable $iterable) use ($amount) {
        return generate\drop($iterable, $amount);
    };
}

function dropWhile(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\dropWhile($iterable, $predicate);
    };
}

function dropWhileIndexed(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\dropWhileIndexed($iterable, $predicate);
    };
}