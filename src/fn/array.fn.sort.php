<?php
declare(strict_types=1);

namespace Arrayly\fn;

function sortBy(array $source, \Closure $comparator, bool $descending):array
{
    $source = (array)$source;
    usort($source, $comparator);
    $sink = (array)$source;
    if ($descending) {
        $sink = array_reverse($sink);
    }

    return (array)$sink;
}