<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function onEach(array $source, \Closure $callback): void
{
    foreach ($source as $v) {
        $callback($v);
    }
}

function onEachIndexed(array $source, \Closure $callback): void
{
    foreach ($source as $k => $v) {
        $callback($k, $v);
    }
}








