<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function pipeTo(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform){
        return generate\pipeTo($iterable, $transform);
    };
}
