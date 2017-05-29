<?php
declare(strict_types=1);

namespace Arrayly\Arrays\fn;

function chunk(array $source, int $batchSize): array
{
    return array_chunk($source, $batchSize, true);
}
function chunkArrayList(array $source, int $batchSize): array
{
    return array_chunk($source, $batchSize, false);
}

