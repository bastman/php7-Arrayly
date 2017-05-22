<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function sortedBy(bool $descending, \Closure $comparator): \Closure
{
    return function (iterable $iterable) use ($comparator, $descending) {
        return generate\sortedBy($iterable, $descending, $comparator);
    };
}

function sortBy(\Closure $comparator): \Closure
{
    return function (iterable $iterable) use ($comparator) {
        return generate\sortBy($iterable, $comparator);
    };
}

function sortByDescending(\Closure $comparator): \Closure
{
    return function (iterable $iterable) use ($comparator) {
        return generate\sortByDescending($iterable, $comparator);
    };
}