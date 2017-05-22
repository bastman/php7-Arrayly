<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function take(int $amount): \Closure
{
    return function (iterable $iterable) use ($amount) {
        return generate\take($iterable, $amount);
    };
}

function takeWhile(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\takeWhile($iterable, $predicate);
    };
}

function takeWhileIndexed(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\takeWhileIndexed($iterable, $predicate);
    };
}