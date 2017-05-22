<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

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