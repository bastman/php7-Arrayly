<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function reduce(array $source, $initialValue, \Closure $reducer)
{
    $accumulatedValue = $initialValue;
    foreach ($source as $k => $v) {
        $accumulatedValue = $reducer($accumulatedValue, $v);
    }

    return $accumulatedValue;
}

function reduceIndexed(array $source, $initialValue, \Closure $reducer)
{
    $accumulatedValue = $initialValue;
    foreach ($source as $k => $v) {
        $accumulatedValue = $reducer($accumulatedValue, $k, $v);
    }

    return $accumulatedValue;
}