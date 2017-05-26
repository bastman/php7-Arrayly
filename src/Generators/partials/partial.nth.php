<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function nth(int $n): \Closure
{
    return function (iterable $iterable) use ($n) {
        return generate\nth($iterable, $n);
    };
}
