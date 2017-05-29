<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function slice(?int $startIndex, ?int $stopIndexExclusive, int $step) {
    return function (iterable $iterable) use ($startIndex, $stopIndexExclusive, $step) {
        return generate\slice($iterable, $startIndex, $stopIndexExclusive, $step);
    };
}
function sliceByOffsetAndLimit(int $offset, ?int $limit, int $step) {
    return function (iterable $iterable) use ($offset, $limit, $step) {
        return generate\sliceByOffsetAndLimit($iterable, $offset, $limit, $step);
    };
}