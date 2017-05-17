<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function groupBy(iterable $iterable, \Closure $keySelector): \Generator
{
    $sink = [];
    foreach ($iterable as $k => $v) {
        $groupKey = $keySelector($v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }
    yield from $sink;
}

function groupByIndexed(iterable $iterable, \Closure $keySelector): \Generator
{
    $sink = [];
    foreach ($iterable as $k => $v) {
        $groupKey = $keySelector($k, $v);
        if (array_key_exists($groupKey, $sink)) {
            $sink[$groupKey][] = $v;
        } else {
            $sink[$groupKey] = [$v];
        }
    }
    yield from $sink;
}