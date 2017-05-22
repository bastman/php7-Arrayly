<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function filter(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function filterIndexed(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($k, $v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}