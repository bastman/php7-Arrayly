<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function filter(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\filter($iterable, $predicate);
    };
}

function filterIndexed(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\filterIndexed($iterable, $predicate);
    };
}

function filterNot(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\filterNot($iterable, $predicate);
    };
}

function filterNotIndexed(\Closure $predicate): \Closure
{
    return function (iterable $iterable) use ($predicate) {
        return generate\filterNotIndexed($iterable, $predicate);
    };
}

function filterNotNull(): \Closure
{
    return function (iterable $iterable){
        return generate\filterNotNull($iterable);
    };
}
