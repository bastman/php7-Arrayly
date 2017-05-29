<?php
declare(strict_types=1);

namespace Arrayly\Util\internals;

use Arrayly\Producers\RewindableProducer;

function iterableToIteratorSupplier(iterable $iterable):\Closure {
    $supplier = function() use($iterable):\Generator{
        yield from $iterable;
    };

    return $supplier;
}

function iterableToRewindableIteratorSupplier(iterable $iterable):\Closure {
    if($iterable instanceof \Generator) {

        throw new \InvalidArgumentException(
            'Parameter iterable must not be instanceof \Generator! since it is not rewindable.'
            .'use ::ofIterableSupplier(fn) for providing rewindable generators.'
        );
    }

    $supplier = function() use($iterable):\Generator{
        yield from $iterable;
    };

    return $supplier;
}

function iteratorSupplierToIterator(\Closure $supplier):\Iterator {
    $iterator = $supplier();
    if(is_array($iterator)) {
        $iterator=new \ArrayIterator($iterator);
    }
    $iterator = requireIterator($iterator);

    return $iterator;
}

function requireIterator(\Iterator $iterator):\Iterator{
    return $iterator;
}

function iterableToTraversable(iterable $iterable):\Traversable {
    if($iterable instanceof \Traversable) {
        return $iterable;
    }
    if ($iterable instanceof \Iterator) {
        return $iterable;
    }
    if ($iterable instanceof \IteratorAggregate) {
        return $iterable->getIterator();
    }

    if (is_array($iterable)) {
        return new \ArrayIterator($iterable);
    }

    throw new \InvalidArgumentException('Argument "iterable" must be traversable!');
}

function iterableToIterator(iterable $iterable):\Iterator {
    if($iterable instanceof \Iterator) {
        return $iterable;
    }
    if (is_array($iterable)) {
        return new \ArrayIterator($iterable);
    }

    if ($iterable instanceof \IteratorAggregate) {
        $iter = $iterable->getIterator();
        if($iter instanceof \Iterator) {

            return $iter;
        }
    }

    throw new \InvalidArgumentException('Argument "iterable" must be has no Iterator!');
}

function requireIterable(iterable $iterable): iterable
{
    return $iterable;
}

function iterableToArray(iterable $source):array {
    if(is_array($source)) {

        return $source;
    }

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