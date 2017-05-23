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

function mapKeysByValue(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\mapKeysByValue($iterable, $keySelector);
    };
}

function mapKeysByValueIndexed(\Closure $keySelector): \Closure
{
    return function (iterable $iterable) use ($keySelector) {
        return generate\mapKeysByValueIndexed($iterable, $keySelector);
    };
}