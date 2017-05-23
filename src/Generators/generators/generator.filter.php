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

function filterNot(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if (! $predicate($v)) {
            yield $k => $v;
        }
    }
}

function filterNotIndexed(iterable $iterable, \Closure $predicate): \Generator
{
    foreach ($iterable as $k => $v) {
        if (! $predicate($k, $v)) {
            yield $k => $v;
        }
    }
}

function filterNotNull(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        if ($v !==null) {
            yield $k => $v;
        }
    }
}
