<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;
use Arrayly\Util\internals as utils;

// Is there any use case for that?
function iterate(iterable $iterable): \Generator
{
    foreach ($iterable as $k => $v) {
        yield $k => $v;
    }
}

function pipeTo(iterable $iterable, \Closure $transform): \Generator
{
    yield from utils\requireIterable($transform($iterable));
}
