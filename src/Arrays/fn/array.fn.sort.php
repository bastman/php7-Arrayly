<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function sortedBy(array $source, bool $descending, \Closure $comparator): array
{
    $source = (array)$source;
    usort($source, $comparator);
    $sink = (array)$source;
    if ($descending) {
        $sink = array_reverse($sink);
    }

    return (array)$sink;
}

function sortBy(array $source, \Closure $comparator): array
{
    return sortedBy($source, false, $comparator);
}

function sortByDescending(array $source, \Closure $comparator): array
{
    return sortedBy($source, true, $comparator);
}