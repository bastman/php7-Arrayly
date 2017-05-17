<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function take(iterable $iterable, int $amount): \Generator
{
    $currentAmount = 0;
    foreach ($iterable as $k => $v) {
        if ($currentAmount >= $amount) {
            break;
        }
        yield $k => $v;
        $currentAmount++;
    }
}

function takeWhile(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if (!$predicate($v)) {

            return; // break???
        }
        yield $k => $v;
    }
}

function takeWhileIndexed(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if (!$predicate($k, $v)) {

            return; // break???
        }
        yield $k => $v;
    }
}