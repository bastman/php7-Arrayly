<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function reverse(iterable $iterable): \Generator
{
    for (end($iterable); ($key=key($iterable))!==null; prev($iterable)){
        yield $key => current($iterable);
    }
}