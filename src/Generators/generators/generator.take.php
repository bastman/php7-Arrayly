<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

use function Arrayly\Util\internals\iterableToArray;

function take(iterable $iterable, int $amount): \Generator
{
    if ($amount <0) {

        throw new \InvalidArgumentException('amount must be >=0! given='.$amount);
    }
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

function takeLast(iterable $iterable, int $offset): \Generator
{
    if($offset===0) {

        return;
    }
    if($offset<0) {

        throw new \InvalidArgumentException('amount must be >=0! given='.$offset);
    }

    $array = iterableToArray($iterable);
    $slice = array_slice($array, -1*$offset, null, true);

    yield from $slice;
}
