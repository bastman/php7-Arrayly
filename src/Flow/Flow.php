<?php
declare(strict_types=1);

namespace Arrayly\Flow;

use Arrayly\Arrayly;
use Arrayly\Iterator\RewindableIterator;
use Arrayly\Sequence\Sequence;
use Arrayly\Sequence\partials as fn;

class Flow
{

    /**
     * @var \Closure[]
     */
    private $commands = [];

    /**
     * @var RewindableIterator
     */
    private $source;

    public static function ofIterable(iterable $source):Flow {
        return new static(RewindableIterator::ofIterable($source), ...[]);
    }

    public static function create():Flow {
        return static::ofIterable([]);
    }

    public function __construct(RewindableIterator $source, \Closure ...$commands)
    {
        $this->source=$source;
        $this->addCommand(...$commands);
    }

    public function copy():Flow {
        return new static($this->source, ...$this->commands);
    }

    public function withSource(iterable $source):Flow {
        if($source instanceof RewindableIterator) {
            return new static($source, ...$this->commands);
        } else {
            return new static(RewindableIterator::ofIterable($source), ...$this->commands);
        }
    }
    public function withSourceSupplier(\Closure $sourceSupplier):Flow {
        return new static(RewindableIterator::ofIterableSupplier($sourceSupplier), ...$this->commands);
    }

    public function withoutSource():Flow {
        return new static(RewindableIterator::ofIterable([]), ...$this->commands);
    }

    private function addCommand(\Closure ...$command) {
        foreach ($command as $cmd) {
            $this->commands[]=$cmd;
        }
    }

    public function map(\Closure $transform): Flow
    {
        $this->addCommand(fn\map($transform));

        return $this;
    }

    public function filter(\Closure $predicate): Flow
    {
        $this->addCommand(fn\filter($predicate));

        return $this;
    }

    public function reducing($initialValue, \Closure $reducer): Flow
    {
        $this->addCommand(fn\reducing($initialValue, $reducer));

        return $this;
    }

    public function collectAsIterable(): iterable
    {
        // work on a fresh (rewinded) iterator source
        $iterable=$this->source->newInstance();
        foreach ($this->commands as $c) {
            $iterable = $c($iterable);
        }

        return $iterable;
    }

    public function collectAsGenerator(): \Generator
    {
        yield from $this->collectAsIterable();
    }

    public function collectAsSequence(): Sequence
    {
        return Sequence::ofIterable($this->collectAsIterable());
    }

    public function collectAsArrayly(): Arrayly
    {
        return Arrayly::ofIterable($this->collectAsIterable());
    }

}