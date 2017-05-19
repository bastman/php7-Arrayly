<?php
declare(strict_types=1);

namespace Arrayly\Sequence\partials;
use Arrayly\Sequence\generators as generate;

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
