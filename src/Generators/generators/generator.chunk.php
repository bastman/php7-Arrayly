<?php
declare(strict_types=1);

namespace Arrayly\Generators\generators;

function chunk(iterable $iterable, int $batchSize): \Generator
{
    if ($batchSize <1) {

        throw new \InvalidArgumentException('batchSize must be >0! given='.$batchSize);
    }
    $currentBatch = [];
    $currentBatchSize = 0;
    foreach ($iterable as $k => $v) {
        $currentBatch[$k] = $v;
        $currentBatchSize++;
        if ($currentBatchSize === $batchSize) {

            yield $currentBatch;

            $currentBatchSize = 0;
            $currentBatch = [];
        }
    }

    if ($currentBatchSize !== 0) {
        yield $currentBatch;
    }
}
