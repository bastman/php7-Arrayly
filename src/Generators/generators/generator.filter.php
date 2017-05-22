<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function filter(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if ($predicate($v)) {
            yield $k => $v;
        }
    }
}

function filterIndexed(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if ($predicate($k, $v)) {
            yield $k => $v;
        }
    }
}
