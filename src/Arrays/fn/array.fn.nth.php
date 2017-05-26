<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

// mimics jmespath step-function of slice-projection
function nth(array $source, int $n): array
{
    if($n===0) {

        throw new \InvalidArgumentException('Argument "n" must be <>0! given='.$n);
    }
    $sink = [];

    if($n<1) {
        $source=array_reverse($source, true);
        $n = abs($n);
    }

    $currentIndex = -1;
    foreach ($source as $k => $v) {
        $currentIndex++;
        if($currentIndex % $n === 0) {
            $sink[$k] = $v;
        }
    }

    return $sink;
}
