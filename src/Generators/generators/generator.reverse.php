<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function reverse(iterable $iterable): \Generator
{
    $entries = [];
    foreach ($iterable as $k =>$v){
        $entries[]= ["k"=>$k, "v"=>$v];
    }
    $reversed = array_reverse($entries, false);
    foreach ($reversed as $entry) {
        yield $entry['k'] => $entry['v'];
    }
}