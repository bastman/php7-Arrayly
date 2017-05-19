<?php
declare(strict_types=1);

namespace Arrayly\Sequence\partials;
use Arrayly\Sequence\generators as generate;

function sortBy(\Closure $comparator, bool $descending): \Closure
{
    return function (iterable $iterable) use ($comparator, $descending) {
        return generate\sortBy($iterable, $comparator, $descending);
    };
}