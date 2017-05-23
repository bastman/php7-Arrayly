<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function chunk(int $batchSize): \Closure
{
    return function (iterable $iterable) use ($batchSize) {
        return generate\chunk($iterable, $batchSize);
    };
}
