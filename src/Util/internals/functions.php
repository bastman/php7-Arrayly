<?php
declare(strict_types=1);

namespace Arrayly\Util\internals;

function requireIsIterator(\Iterator $iterator):\Iterator{
    return $iterator;
}

function requireIterable(iterable $iterable): iterable
{
    return $iterable;
}

function iterableToArray(iterable $source):array {
    $sink = [];
    foreach ($source as $k => $v) {
        $sink[$k] = $v;
    }

    return $sink;
}

/**
 * @param \Closure[] ...$closure
 * @return \Closure[]
 */
function requireClosureListFromVarArgs(\Closure ...$closure):array {
    return $closure;
}

/**
 * @param \Closure[] $source
 * @param \Closure[] ...$closure
 * @return \Closure[]
 */
function appendClosureToList(array $source, \Closure ...$closure):array {
    $sink=requireClosureListFromVarArgs(...$source);
    foreach ($closure as $cls) {
        $sink[]=$cls;
    }

    return $sink;
}