<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;
use Arrayly\Util\internals as utils;


function pipeTo(iterable $iterable, \Closure $transform): \Generator
{
    yield from utils\requireIterable($transform($iterable));
}
