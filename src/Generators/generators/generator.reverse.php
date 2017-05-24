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
        yield $reversed['k'] => $reversed['v'];
    }

    /*
    $stack = new \SplStack();
    foreach ($iterable as $k =>$v){
        $stack->push(["k"=>$k, "v"=>$v]);
    }

    while (!$stack->isEmpty()){
        $popped=$stack->pop();
        yield $popped['k']  =>  $popped['v'];
    }
    */

}