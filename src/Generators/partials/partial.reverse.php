<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function reverse(): \Closure
{
    return function (iterable $iterable){
        return generate\reverse($iterable);
    };
}