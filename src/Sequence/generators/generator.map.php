<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function map(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k => $transform($v);
    }
}

function mapIndexed(iterable $iterable, \Closure $transform): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k => $transform($k, $v);
    }
}

function mapKeys(iterable $iterable, \Closure $keySelector): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $keySelector($v) => $v;
    }
}

function mapKeysIndexed(iterable $iterable, \Closure $keySelector): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $keySelector($k, $v) => $v;
    }
}