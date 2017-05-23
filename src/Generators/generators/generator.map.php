<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

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

function mapKeysByValue(iterable $iterable, \Closure $keySelector): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $keySelector($v) => $v;
    }
}

function mapKeysByValueIndexed(iterable $iterable, \Closure $keySelector): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $keySelector($k, $v) => $v;
    }
}