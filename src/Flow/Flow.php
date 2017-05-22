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

    private function run(): iterable
    {
        // work on a fresh (rewinded) iterator source
        $iterable=$this->source->newInstance();
        foreach ($this->commands as $c) {
            $iterable = $c($iterable);
        }

        return $iterable;
    }

    public function collect():FlowResult {
        $iter = $this->run();
        $sink=[];
        foreach ($iter as $k=>$v) {
            $sink[$k] = $v;
        }

        return FlowResult::ofArray($sink);
    }


    public function reducing($initialValue, \Closure $reducer): Flow
    {
        return $this->withCommandAppended(fn\reducing($initialValue, $reducer));
    }

    public function reducingIndexed($initialValue, \Closure $reducer): Flow
    {
        return $this->withCommandAppended(fn\reducingIndexed($initialValue, $reducer));
    }

    public function pipeTo(\Closure $transform): Flow
    {
        return $this->withCommandAppended(fn\pipeTo($transform));
    }

    public function keys(): Flow
    {
        return $this->withCommandAppended(fn\keys());
    }

    public function values(): Flow
    {
        return $this->withCommandAppended(fn\values());
    }

    public function flip(): Flow
    {
        return $this->withCommandAppended(fn\flip());
    }

    public function reverse(bool $preserveKeys): Flow
    {
        return $this->withCommandAppended(fn\reverse($preserveKeys));
    }

    public function onEach(\Closure $callback): Flow
    {
        return $this->withCommandAppended(fn\onEach($callback));
    }

    public function onEachIndexed(\Closure $callback): Flow
    {
        return $this->withCommandAppended(fn\onEachIndexed($callback));
    }

    public function map(\Closure $transform): Flow
    {
        return $this->withCommandAppended(fn\map($transform));
    }

    public function mapIndexed(\Closure $transform): Flow
    {
        return $this->withCommandAppended(fn\mapIndexed($transform));
    }

    public function mapKeys(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(fn\mapKeys($keySelector));
    }

    public function mapKeysIndexed(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(fn\mapKeysIndexed($keySelector));
    }

    public function filter(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\filter($predicate));
    }

    public function filterIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\filterIndexed($predicate));
    }

    public function flatMap(\Closure $transform): Flow
    {
        return $this->withCommandAppended(fn\flatMap($transform));
    }
    public function flatMapIndexed(\Closure $transform): Flow
    {
        return $this->withCommandAppended(fn\flatMapIndexed($transform));
    }
    public function groupBy(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(fn\groupBy($keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(fn\groupByIndexed($keySelector));
    }

    public function take(int $amount): Flow
    {
        return $this->withCommandAppended(fn\take($amount));
    }

    public function drop(int $amount): Flow
    {
        return $this->withCommandAppended(fn\drop($amount));
    }

    public function takeWhile(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\takeWhile($predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\takeWhileIndexed($predicate));
    }

    public function dropWhile(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\dropWhile($predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(fn\dropWhileIndexed($predicate));
    }

    public function sortBy(\Closure $comparator, bool $descending): Flow
    {
        return $this->withCommandAppended(fn\sortBy($comparator, $descending));
    }



}