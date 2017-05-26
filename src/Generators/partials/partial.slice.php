<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function slice(int $offset, ?int $length): \Closure
{
    return function (iterable $iterable) use ($offset, $length) {
        return generate\slice($iterable, $offset, $length);
    };
}

function sliceSubset(?int $startIndex, ?int $stopIndexExclusive, int $step=1) {
    return function (iterable $iterable) use ($startIndex, $stopIndexExclusive, $step) {
        return generate\sliceSubset($iterable, $startIndex, $stopIndexExclusive, $step);
    };
}
