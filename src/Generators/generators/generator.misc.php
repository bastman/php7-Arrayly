<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

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
