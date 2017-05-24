<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function take(array $source, int $amount): array
{
    $sink = [];
    $currentAmount = 0;
    foreach ($source as $k => $v) {
        if ($currentAmount >= $amount) {

            break;
        }
        $sink[$k] = $v;
        $currentAmount++;
    }

    return $sink;
}

function takeWhile(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($v)) {
            $sink[$k] = $v;
        } else {

            break;
        }

    }

    return $sink;
}

function takeWhileIndexed(array $source, \Closure $predicate): array
{
    $sink = [];
    foreach ($source as $k => $v) {
        if ($predicate($k, $v)) {
            $sink[$k] = $v;
        } else {

            break;
        }

    }

    return $sink;
}

function takeLast(array $source, int $offset): array
{
    if($offset===0) {

        return [];
    }
    if($offset<0) {

        throw new \InvalidArgumentException('amount must be >=0! given='.$offset);
    }

    return array_slice($source, -1*$offset, null, true);
}
