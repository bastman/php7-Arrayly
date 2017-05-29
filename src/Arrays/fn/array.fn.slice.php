<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

// php array slice(offset, length)
function slice(array $source, int $offset, ?int $length): array
{
    $preserveKeys = true;

    if($length===0) {
        return [];
    }

    return array_slice($source, $offset, $length, $preserveKeys);
}
// mimics JmesPath slice(startIndex,endExclusiveIndex, step)
// see: https://github.com/jmespath/jmespath.php/blob/master/src/Utils.php
function sliceSubset(array $source, ?int $startIndex, ?int $stopIndexExclusive, int $step): array
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

