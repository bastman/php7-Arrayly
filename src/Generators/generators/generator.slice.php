<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;


use function Arrayly\Util\internals\iterableToArray;
use Arrayly\Arrays\fn as arrays;


// mimics JmesPath/Python slice(startIndex,endExclusiveIndex, step)
// see: https://github.com/jmespath/jmespath.php/blob/master/src/Utils.php
function slice(iterable $iterable, ?int $startIndex, ?int $stopIndexExclusive, int $step): \Generator {

    if($step<1) {
        // Who will ever understand expressions with negative step size? guys working at nasa ???
        throw new \InvalidArgumentException('Argument "step" must be >0 !');
    }

    if(
        ($startIndex===null || $startIndex===0)
        && $stopIndexExclusive===null
        && ($step===1)
    ){
        // nothing changed
        yield from $iterable;

        return;
    }

    $useLazyImpl = (
        $step>0
        && ($startIndex===null || (is_int($startIndex) && $startIndex>=0))
        && ($stopIndexExclusive===null || (is_int($stopIndexExclusive) && $stopIndexExclusive>=0))
    );
    if(!$useLazyImpl) {
        //delegate to array functions
        yield from arrays\slice(iterableToArray($iterable), $startIndex, $stopIndexExclusive, $step);

        return;
    }

    // use generator approach
    if($startIndex===null) {
        $startIndex = 0;
    }

    $currentIndex=-1;
    $findAt = $startIndex;
    foreach ($iterable as $k=>$v) {
        $currentIndex++;

        // apply constraint: startIndex
        if($currentIndex<$startIndex) {
            // ignore
            continue;
        }

        if($stopIndexExclusive!==null) {
            // apply constraint: stopIndexExclusive
            if($currentIndex>=$stopIndexExclusive) {
                // done
                break;
            }
        }

        if($currentIndex===$findAt) {
            yield $k => $v;

            $findAt += $step;
        }
    }

}

// replacement for php's array_slice function - which covers too many concerns,
function sliceByOffsetAndLimit(iterable $iterable, int $offset, ?int $limit, int $step): \Generator
{
    if($step<1) {
        // Who will ever understand expressions with negative step size? guys working at nasa ???
        throw new \InvalidArgumentException('Argument "step" must be >0 !');
    }

    if(is_int($limit) && $limit<0) {
        throw new \InvalidArgumentException('Argument "limit" must be >=0 !');
    }

    if($limit===0) {
        // empty result
        yield from [];

        return;
    }

    if(
        ($step===1)
        && ($offset===null || $offset===0)
        && $limit===null
    ){
        // nothing changed
        yield from $iterable;

        return;
    }

    $useLazyImpl = (
        $step>0
        && ($offset===null || (is_int($offset) && $offset>=0))
    );
    if(!$useLazyImpl) {
        //delegate to array functions
        yield from arrays\sliceByOffsetAndLimit(iterableToArray($iterable), $offset, $limit, $step);

        return;
    }

    $currentIndex=-1;
    $currentLength = 0;
    $findAt = $offset;
    foreach ($iterable as $k=>$v) {
        $currentIndex++;
        // apply constraint: offset
        if($currentIndex<$offset) {
            // ignore
            continue;
        }
        if(is_int($limit)) {
            // apply constraint: limit
            if($currentLength>=$limit) {
                // done
                break;
            }
        }

        // apply constraint: step
        if($currentIndex===$findAt) {
            yield $k=>$v;
            $currentLength ++;

            $findAt += $step;
        }
    }
}

