<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function reducing($initialValue, \Closure $reducer): \Closure
{
    return function (iterable $iterable) use ($initialValue, $reducer) {
        return generate\reducing($iterable, $initialValue, $reducer);
    };
}

function reducingIndexed($initialValue, \Closure $reducer): \Closure
{
    return function (iterable $iterable) use ($initialValue, $reducer) {
        return generate\reducingIndexed($iterable, $initialValue, $reducer);
    };
}