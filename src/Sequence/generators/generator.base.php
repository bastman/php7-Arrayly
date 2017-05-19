<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function requireIterable(iterable $iterable):iterable
{
    return $iterable;
}

// Is there any use case for that?
function iterate(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k => $v;
    }
}

function pipeTo(iterable $iterable, \Closure $transform): \Generator
{
    yield from requireIterable($transform($iterable));
}
