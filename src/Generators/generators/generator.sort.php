<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function sortBy(iterable $iterable, \Closure $comparator, bool $descending): \Generator
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