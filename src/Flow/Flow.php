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
        return new static(RewindableIterator::ofIterable($source), []);
    }

    public static function create():Flow {
        return static::ofIterable([]);
    }

    /**
     * @param \Closure[] ...$closure
     * @return \Closure[]
     */
    private static function requireClosureList(\Closure ...$closure):array {
        return $closure;
    }

    private static function appendClosure(array $source, \Closure ...$closure):array {
        $sink=static::requireClosureList(...$source);
        foreach ($closure as $cls) {
            $sink[]=$cls;
        }

        return $sink;
    }

    public function __construct(RewindableIterator $source, array $commands)
    {
        $this->commands = static::requireClosureList(...$commands);
        $this->source=$source;
    }

    public function copy():Flow {
        return new static($this->source, $this->commands);
    }

    public function withSource(iterable $source):Flow {
        if($source instanceof RewindableIterator) {
            return new static($source, $this->commands);
        } else {
            return new static(RewindableIterator::ofIterable($source), $this->commands);
        }
    }
    public function withSourceSupplier(\Closure $sourceSupplier):Flow {
        return new static(RewindableIterator::ofIterableSupplier($sourceSupplier), $this->commands);
    }

    public function withoutSource():Flow {
        return new static(RewindableIterator::ofIterable([]), $this->commands);
    }

    private function withCommandAppended(\Closure ...$commands):Flow {
        $newInstance = $this->copy();
        $newInstance->commands = static::appendClosure(
            $newInstance->commands,
            ...$commands
        );

        return $newInstance;
    }

    public function map(\Closure $transform): Flow
    {
        return $this->withCommandAppended(fn\map($transform));
    }

    public function filter(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\filter($predicate));
    }

    public function reducing($initialValue, \Closure $reducer): Flow
    {
        return $this->withCommandAppended(fn\reducing($initialValue, $reducer));
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