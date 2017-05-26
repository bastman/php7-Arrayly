<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

use Arrayly\Arrayly;
use function Arrayly\Util\internals\iterableToArray;

function slice(iterable $iterable, int $offset, ?int $length): \Generator
{
    $preserveKeys = true;

    // offset:  If offset is non-negative, the sequence will start at that offset in the array.
    //          If offset is negative, the sequence will start that far from the end of the array.
    // --> lazy generator approach requires: offset>=0

    // length:  If length is given and is positive, then the sequence will have up to that many elements in it.
    //          If length is given and is negative then the sequence will stop that many elements from the end of the array.
    //          If it is null, then the sequence will have everything from offset up until the end of the array.
    // --> lazy generator approach requires: length>=0

    if($length===0) {
        yield from [];

        return;
    }

    $useLazy = ($offset>=0) && is_int($length) && $length>0;
    if($useLazy) {
        $currentOffset = -1;
        $currentLength = 0;
        foreach ($iterable as $k=>$v) {
            $currentOffset++;
            if($currentLength>=$length) {

                return;
            }
            if($currentOffset>=$offset) {
                yield $k=>$v;
                $currentLength++;
            }
        }

        return;
    }

    // use array functions
    $sink = array_slice(iterableToArray($iterable), $offset, $length, $preserveKeys);

    yield from $sink;
}

// mimics JmesPath slice(startIndex,endExclusiveIndex, step)
// see: https://github.com/jmespath/jmespath.php/blob/master/src/Utils.php
function sliceSubset(iterable $iterable, ?int $startIndex, ?int $stopIndexExclusive, int $step=1): \Generator {
    // for now - delegate to array functions

    yield from \Arrayly\Arrays\fn\sliceSubset(iterableToArray($iterable), $startIndex, $stopIndexExclusive, $step);
}


