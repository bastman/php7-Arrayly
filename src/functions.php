<?php
declare(strict_types=1);
namespace Arrayly;

function listOf(...$values):ArrayList {
    return ArrayList::ofIterable(...$values);
}
function listOfIterable(iterable $iterable):ArrayList {
    return ArrayList::ofIterable($iterable);
}

function mapOfIterable(iterable $iterable):ArrayMap {
    return ArrayMap::ofIterable($iterable);
}