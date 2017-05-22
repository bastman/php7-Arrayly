<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function groupBy(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\groupBy($iterable, $keySelector);
    };
}

function groupByIndexed(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\groupByIndexed($iterable, $keySelector);
    };
}