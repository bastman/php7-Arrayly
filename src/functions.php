<?php
declare(strict_types=1);

use Arrayly\Arrayly;
use Arrayly\ArrayList;

function listOf(...$values):ArrayList {
    return ArrayList::ofIterable(...$values);
}
function listOfIterable(iterable $iterable):ArrayList {
    return ArrayList::ofIterable($iterable);
}

function mapOfIterable(iterable $iterable):Arrayly {
    return Arrayly::ofIterable($iterable);
}