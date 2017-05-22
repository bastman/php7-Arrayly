<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function keys(): \Closure
{
    return function (iterable $iterable) {
        return generate\keys($iterable);
    };
}

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
    return function (iterable $iterable) use ($preserveKeys){
        return generate\reverse($iterable, $preserveKeys);
    };
}