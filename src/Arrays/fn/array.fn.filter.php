<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function filter(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function filterIndexed(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($k, $v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function filterNot(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if (! $predicate($v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function filterNotIndexed(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if (! $predicate($k, $v)) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}

function filterNotNull(array $source): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if($v!==null) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}