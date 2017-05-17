<?php
declare(strict_types=1);

namespace Arrayly\fn;

function drop(array $source, int $amount):array
{
    $sink = [];
    $dropped = 0;
    foreach ($source as $k => $v) {
        if ($dropped < $amount) {
            $dropped++;
        } else {
            $sink[$k] = $v;
        }
    }

    return $sink;
}


function dropWhile(array $source, \Closure $predicate):array
{
    $sink = [];
    $failed = false;
    foreach ($source as $k => $v) {
        if (!$failed && !$predicate($v)) {
            $failed = true;
        }
        if ($failed) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function dropWhileIndexed(array $source,\Closure $predicate):array
{
    $sink = [];
    $failed = false;
    foreach ($source as $k => $v) {
        if (!$failed && !$predicate($k, $v)) {
            $failed = true;
        }
        if ($failed) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}
