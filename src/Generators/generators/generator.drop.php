<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function drop(iterable $iterable, int $amount): \Generator
{
    $dropped = 0;
    foreach ($iterable as $k => $v) {
        if ($dropped < $amount) {
            $dropped++;
        } else {
            yield $k => $v;
        }
    }
}

function dropWhile(iterable $iterable, \Closure $predicate): \Generator
{
    $failed = false;
    foreach ($iterable as $key => $value) {
        if (!$failed && !$predicate($value)) {
            $failed = true;
        }
        if ($failed) {
            yield $key => $value;
        }
    }
}

function dropWhileIndexed(iterable $iterable, \Closure $predicate): \Generator
{
    $failed = false;
    foreach ($iterable as $key => $value) {
        if (!$failed && !$predicate($key, $value)) {
            $failed = true;
        }
        if ($failed) {
            yield $key => $value;
        }
    }
}