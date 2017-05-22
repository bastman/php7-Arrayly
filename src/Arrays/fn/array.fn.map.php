<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function map(array $source, \Closure $transform): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$k] = $transform($v);
    }

    return $sink;
}

function mapIndexed(array $source, \Closure $transform): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$k] = $transform($k, $v);
    }

    return $sink;
}

function mapKeys(array $source, \Closure $keySelector): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$keySelector($v)] = $v;
    }

    return $sink;
}

function mapKeysIndexed(array $source, \Closure $keySelector): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$keySelector($k, $v)] = $v;
    }

    return $sink;
}