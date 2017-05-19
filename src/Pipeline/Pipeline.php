<?php
declare(strict_types=1);

namespace Arrayly\Pipeline;

use Arrayly\Arrayly;
//use Arrayly\Pipeline\fn as fn;
use Arrayly\Sequence\Sequence;
use Arrayly\Sequence\partials as fn;
class Pipeline
{

    /**
     * @var \Closure[]
     */
    private $commands = [];

    private function addCommand(\Closure ...$command) {
        foreach ($command as $cmd) {
            $this->commands[]=$cmd;
        }
    }

    public function map(\Closure $transform): Pipeline
    {
        $this->addCommand(fn\map($transform));

        return $this;
    }

    public function filter(\Closure $predicate): Pipeline
    {
        $this->addCommand(fn\filter($predicate));

        return $this;
    }

    public function reducing($initialValue, \Closure $reducer): Pipeline
    {
        $this->addCommand(fn\reducing($initialValue, $reducer));

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