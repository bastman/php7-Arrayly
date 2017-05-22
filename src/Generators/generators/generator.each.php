<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function onEach(iterable $iterable, \Closure $callback): \Generator
{
    foreach ($iterable as $k => $v) {
        $callback($v);
        yield $k => $v;
    }
}

function onEachIndexed(iterable $iterable, \Closure $callback): \Generator
{
    foreach ($iterable as $k => $v) {
        $callback($k, $v);
        yield $k => $v;
    }
}