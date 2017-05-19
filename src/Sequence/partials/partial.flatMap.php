<?php
declare(strict_types=1);

namespace Arrayly\Sequence\partials;
use Arrayly\Sequence\generators as generate;

function flatMap(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\flatMap($iterable, $transform);
    };
}

function flatMapIndexed(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\flatMapIndexed($iterable, $transform);
    };
}


