<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function groupBy(array $source, \Closure $keySelector): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $groupKey = $keySelector($v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }

    return $sink;
}

function groupByIndexed(array $source, \Closure $keySelector): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $groupKey = $keySelector($k, $v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }

    return $sink;
}