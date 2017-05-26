<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;
// mimics jmespath step-function of slice-projection
function nth(iterable $iterable, int $n): \Generator
{
    if($n===0) {

        throw new \InvalidArgumentException('Argument "n" must be <>0! given='.$n);
    }

    if($n<1) {
        // fallback to arrays functions
        $iterable = reverse($iterable);
        $n = abs($n);
    }

    $currentIndex = -1;
    foreach ($iterable as $k => $v) {
        $currentIndex++;
        if($currentIndex % $n === 0) {
            yield $k => $v;
        }
    }

}