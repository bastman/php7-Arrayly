<?php
declare(strict_types=1);

namespace Arrayly\fn;

function requireIterable(iterable $source): iterable
{
    return $source;
}

function keys(array $source, bool $strict = true): array
{
    return array_keys($source, null, $strict);
}

function values(array $source): array
{
    return array_values($source);
}

function flip(array $source): array
{
    return array_flip($source);
}

function shuffle(array $source, int $times): array
{
    $sink = (array)$source;
    $i = 0;
    while ($i < $times) {
        \shuffle($sink);
    }

    return (array)$sink;
}

function count(array $source): int
{
    return \count($source);
}

function reverse(array $source, bool $preserveKeys): array
{
    return array_reverse($source, $preserveKeys);
}

function hasKey(array $source, $key): bool
{
    return array_key_exists($key, $source);
}