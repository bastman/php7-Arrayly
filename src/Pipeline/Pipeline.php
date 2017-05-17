<?php
declare(strict_types=1);

namespace Arrayly\Pipeline;

use Arrayly\Arrayly;
use Arrayly\Pipeline\fn as fn;
use Arrayly\Sequence\Sequence;

class Pipeline
{

    private $commands = [];

    public function map(\Closure $transform): Pipeline
    {
        $gen = fn\map($transform);
        $this->commands[] = $gen;

        return $this;
    }

    public function filter(\Closure $predicate): Pipeline
    {
        $gen = fn\filter($predicate);
        $this->commands[] = $gen;

        return $this;
    }

    public function reduce($initialValue, \Closure $reducer): Pipeline
    {
        $gen = fn\reduce($initialValue, $reducer);
        $this->commands[] = $gen;

        return $this;
    }

    public function collect(iterable $source): iterable
    {
        $current = $source;
        foreach ($this->commands as $c) {
            $current = $c($current);
        }

        return $current;
    }

    public function collectAsArray(iterable $source): array
    {
        return $this->collectAsSequence($source)
            ->toArray();
    }

    public function collectAsSequence(iterable $source): Sequence
    {
        return new Sequence($this->execute($source));
    }

    public function execute(iterable $source): \Generator
    {
        $current = $source;
        foreach ($this->commands as $c) {
            $current = $c($current);
        }

        yield from $current;
    }

    public function collectAsArrayly(iterable $source): Arrayly
    {
        return $this->collectAsSequence($source)
            ->toArrayly();
    }


}