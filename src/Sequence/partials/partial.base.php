<?php
declare(strict_types=1);

namespace Arrayly\Sequence\partials;
use Arrayly\Sequence\generators as generate;

// Is there any use case for that?
function iterate(): \Closure
{
    return function (iterable $iterable) {
        return generate\iterate($iterable);
    };
}

function pipeTo(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform){
        return generate\pipeTo($iterable, $transform);
    };
}
