<?php
declare(strict_types=1);

namespace Arrayly\Generators\partials;
use Arrayly\Generators\generators as generate;

function map(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\map($iterable, $transform);
    };
}

function mapIndexed(\Closure $transform): \Closure
{
    return function (iterable $iterable) use ($transform) {
        return generate\mapIndexed($iterable, $transform);
    };
}

function mapKeys(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\mapKeys($iterable, $keySelector);
    };
}

function mapKeysIndexed(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\mapKeysIndexed($iterable, $keySelector);
    };
}