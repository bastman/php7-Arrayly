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
function sliceSubset(array $source, ?int $startIndex, ?int $stopIndexExclusive, ?int $step): array
{
    if($step===0) {
        $step=1;
    }

    if(
        ($startIndex===null || $startIndex===0)
        && $stopIndexExclusive===null
        && ($step===null || $step===1)
    ){
        // nothing changed
        return $source;
    }


    $adjustEndpoint = function (int $length, int $endpoint, int $step):int {

        if ($endpoint < 0) {
            $endpoint += $length;
            if ($endpoint < 0) {
                $endpoint = $step < 0 ? -1 : 0;
            }
        } elseif ($endpoint >= $length) {
            $endpoint = $step < 0 ? $length - 1 : $length;
        }

        return $endpoint;
    };

    $sourceItemsCount = count($source);

    if ($startIndex === null) {
        $startIndex = $step < 0 ? $sourceItemsCount - 1 : 0;
    } else {
        $startIndex = $adjustEndpoint($sourceItemsCount, $startIndex, $step);
    }

    if ($stopIndexExclusive === null) {
        $stopIndexExclusive = $step < 0 ? -1 : $sourceItemsCount;
    } else {
        $stopIndexExclusive = $adjustEndpoint($sourceItemsCount, $stopIndexExclusive, $step);
    }

    $sink=[];
    if ($step > 0) {
        $currentIndex=-1;
        $findAt = $startIndex;
        //var_dump("start: ".$startIndex, "stop: ".$stopIndexExclusive, "step: ".$step);
        foreach ($source as $k=>$v) {

            $currentIndex++;
            //var_dump("findAt=".$findAt." currentIndex: ".$currentIndex);
            if($currentIndex<$startIndex) {
                //var_dump("SKIP");
                // ignore
                continue;
            }
            if($currentIndex>=$stopIndexExclusive) {
                //var_dump("DONE");
                // done
                break;
            }
            if($currentIndex===$findAt) {
                //var_dump("TAKE");
                $sink[$k] = $v;

                $findAt+=$step;
            }

        }

    } else {
        for ($i = $startIndex; $i > $stopIndexExclusive; $i += $step) {
            $value=$source[$i];
            $key = key($source);
            $sink[$key] = $value;
        }
    }

    return $sink;
}

