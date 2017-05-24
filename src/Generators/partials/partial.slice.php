<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function slice(int $offset, ?int $length): \Closure
{
    return function (iterable $iterable) use ($offset, $length) {
        return generate\slice($iterable, $offset, $length);
    };
}
