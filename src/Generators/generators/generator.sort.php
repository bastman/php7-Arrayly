<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function sortedBy(iterable $iterable, bool $descending, \Closure $comparator): \Generator
{
    $source = [];
    foreach ($iterable as $k => $v) {
        $source[$k] = $v;
    }
    usort($source, $comparator);
    $sink = (array)$source;
    if ($descending) {
        $sink = array_reverse($sink);
    }

    yield from $sink;
}

function sortBy(iterable $iterable, \Closure $comparator): \Generator
{
    return sortedBy($iterable, false, $comparator);
}

function sortByDescending(iterable $iterable, \Closure $comparator): \Generator
{
    return sortedBy($iterable, true, $comparator);
}