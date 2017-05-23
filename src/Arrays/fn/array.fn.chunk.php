<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function chunk(array $source, int $batchSize, bool $preserveKeys): array
{
    return array_chunk($source, $batchSize, $preserveKeys);
}

