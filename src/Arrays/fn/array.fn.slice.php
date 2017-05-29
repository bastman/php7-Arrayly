<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;


// mimics JmesPath slice(startIndex,endExclusiveIndex, step)
// see: https://github.com/jmespath/jmespath.php/blob/master/src/Utils.php
function slice(array $source, ?int $startIndex, ?int $stopIndexExclusive, int $step): array
{
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
        return $source;
    }

    $adjustEndpoint = function (int $length, int $endpoint, int $step):int {
        if($step<1) {
            throw new \InvalidArgumentException('Argument "step" must be >0 !');
        }
        if ($endpoint < 0) {
            $endpoint += $length;
            if ($endpoint < 0) {
                $endpoint = 0;
            }
        } elseif ($endpoint >= $length) {
            $endpoint = $length;
        }

        return $endpoint;
    };

    $sourceItemsCount = count($source);

    if ($startIndex === null) {
        $startIndex = 0;
    } else {
        $startIndex = $adjustEndpoint($sourceItemsCount, $startIndex, $step);
    }

    if ($stopIndexExclusive === null) {
       $stopIndexExclusive = $sourceItemsCount;
    } else {
        $stopIndexExclusive = $adjustEndpoint($sourceItemsCount, $stopIndexExclusive, $step);
    }

    $sink=[];

    $currentIndex=-1;
    $findAt = $startIndex;
    foreach ($source as $k=>$v) {
        $currentIndex++;
        if($currentIndex<$startIndex) {
            // ignore
            continue;
        }
        if($currentIndex>=$stopIndexExclusive) {
            // done
            break;
        }
        if($currentIndex===$findAt) {
            $sink[$k] = $v;

            $findAt += $step;
        }
    }

    return $sink;
}

// replacement for php's array_slice function - which covers too many concerns,
function sliceByOffsetAndLimit(array $source, int $offset, ?int $limit, int $step): array
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
        return [];
    }

    if(
        ($step===1)
        && ($offset===null || $offset===0)
        && $limit===null
    ){
        // nothing changed
        return $source;
    }

    $adjustEndpoint = function (int $length, int $endpoint, int $step):int {
        if($step<1) {
            throw new \InvalidArgumentException('Argument "step" must be >0 !');
        }
        if ($endpoint < 0) {
            $endpoint += $length;
            if ($endpoint < 0) {
                $endpoint = 0;
            }
        } elseif ($endpoint >= $length) {
            $endpoint = $length;
        }

        return $endpoint;
    };

    if ($offset === null) {
        $offset = 0;
    } else {
        $offset = $adjustEndpoint(count($source), $offset, $step);
    }

    $sink=[];

    $currentIndex=-1;
    $currentLength = 0;
    $findAt = $offset;
    foreach ($source as $k=>$v) {
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
            $sink[$k] = $v;
            $currentLength ++;

            $findAt += $step;
        }
    }

    return $sink;
}
