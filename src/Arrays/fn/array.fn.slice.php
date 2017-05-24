<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function slice(array $source, int $offset, ?int $length): array
{
    $preserveKeys = true;

    if($length===0) {
        return [];
    }

    return array_slice($source, $offset, $length, $preserveKeys);
}

