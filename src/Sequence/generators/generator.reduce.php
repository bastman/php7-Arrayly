<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function reducing(iterable $iterable, $initialValue, \Closure $reducer): \Generator
{
    $accumulatedValue = $initialValue;
    foreach ($iterable as $k => $v) {
        $accumulatedValue = $reducer($accumulatedValue, $v);
    }

    yield $accumulatedValue;
}

function reducingIndexed(iterable $iterable, $initialValue, \Closure $reducer): \Generator
{
    $accumulatedValue = $initialValue;
    foreach ($iterable as $k => $v) {
        $accumulatedValue = $reducer($accumulatedValue, $k, $v);
    }

    yield $accumulatedValue;
}