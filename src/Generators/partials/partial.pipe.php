<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function pipe(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform){
        return generate\pipe($iterable, $transform);
    };
}
