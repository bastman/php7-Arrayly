<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function keys(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k;
    }
}

function values(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $v;
    }
}

function flip(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $v => $k;
    }
}

function reverse(iterable $iterable, bool $preserveKeys): \Generator
{
    $source = [];
    foreach ($iterable as $k => $v) {
        $source[$k] = $v;
    }
    $sink = array_reverse($source, $preserveKeys);
    foreach ($sink as $k => $v) {
        yield $k => $v;
    }
}