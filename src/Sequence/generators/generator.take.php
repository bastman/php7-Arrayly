<?php
declare(strict_types=1);

namespace Arrayly\Sequence\generators;

function take(iterable $iterable, int $amount): \Generator
{
    $currentAmount = 0;
    foreach ($iterable as $k => $v) {
        if ($currentAmount >= $amount) {
            break;
        }
        yield $k => $v;
        $currentAmount++;
    }
}