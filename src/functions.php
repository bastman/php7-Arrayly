<?php
declare(strict_types=1);
namespace Arrayly;
use Arrayly\Producers\RewindableProducer;
use Arrayly\Util\internals as utils;

function listOf(...$values):ArrayList {
    return ArrayList::ofIterable($values);
}
function listOfIterable(iterable $iterable):ArrayList {
    return ArrayList::ofIterable($iterable);
}

function mapOfIterable(iterable $iterable):ArrayMap {
    return ArrayMap::ofIterable($iterable);
}

function sequenceOfIterable(iterable $iterable):Sequence {
    return Sequence::ofIterable($iterable);
}
function sequenceOfIteratorSupplier(\Closure $supplier):Sequence {
    return Sequence::ofIterable(utils\iteratorSupplierToIterator($supplier));
}
function sequenceOfRewindableIteratorSupplier(\Closure $supplier):Sequence {
    return Sequence::ofIterable(RewindableProducer::ofIteratorSupplier($supplier));
}


function flowOfIterable(iterable $iterable):Flow {
    return Flow::create()
        ->withProducerOfIterable($iterable);
}
function flowOfRewindableIteratorSupplier(\Closure $supplier):Flow {
    return Flow::create()
        ->withProducerOfIteratorSupplier($supplier);
}