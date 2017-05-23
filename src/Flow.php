<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Flow\FlowSink;
use Arrayly\Iterator\RewindableIterator;

use Arrayly\Generators\partials as generate;
use Arrayly\Util\internals as utils;

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

    public function __construct(RewindableIterator $source, array $commands)
    {
        $this->commands = utils\requireClosureListFromVarArgs(...$commands);
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
        $newInstance->commands = utils\appendClosureToList(
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

    public function collect():FlowSink {
        $iter = $this->run();
        $sink=[];
        foreach ($iter as $k=>$v) {
            $sink[$k] = $v;
        }

        return FlowSink::ofArray($sink);
    }


    public function reducing($initialValue, \Closure $reducer): Flow
    {
        return $this->withCommandAppended(generate\reducing($initialValue, $reducer));
    }

    public function reducingIndexed($initialValue, \Closure $reducer): Flow
    {
        return $this->withCommandAppended(generate\reducingIndexed($initialValue, $reducer));
    }

    public function pipeTo(\Closure $transform): Flow
    {
        return $this->withCommandAppended(generate\pipeTo($transform));
    }

    public function keys(): Flow
    {
        return $this->withCommandAppended(generate\keys());
    }

    public function values(): Flow
    {
        return $this->withCommandAppended(generate\values());
    }

    public function flip(): Flow
    {
        return $this->withCommandAppended(generate\flip());
    }

    public function reverse(bool $preserveKeys): Flow
    {
        return $this->withCommandAppended(generate\reverse($preserveKeys));
    }

    public function onEach(\Closure $callback): Flow
    {
        return $this->withCommandAppended(generate\onEach($callback));
    }

    public function onEachIndexed(\Closure $callback): Flow
    {
        return $this->withCommandAppended(generate\onEachIndexed($callback));
    }

    public function map(\Closure $transform): Flow
    {
        return $this->withCommandAppended(generate\map($transform));
    }

    public function mapIndexed(\Closure $transform): Flow
    {
        return $this->withCommandAppended(generate\mapIndexed($transform));
    }

    public function mapKeys(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(generate\mapKeys($keySelector));
    }

    public function mapKeysIndexed(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(generate\mapKeysIndexed($keySelector));
    }

    public function filter(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\filter($predicate));
    }

    public function filterIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\filterIndexed($predicate));
    }

    public function flatMap(\Closure $transform): Flow
    {
        return $this->withCommandAppended(generate\flatMap($transform));
    }
    public function flatMapIndexed(\Closure $transform): Flow
    {
        return $this->withCommandAppended(generate\flatMapIndexed($transform));
    }
    public function groupBy(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(generate\groupBy($keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(generate\groupByIndexed($keySelector));
    }

    public function take(int $amount): Flow
    {
        return $this->withCommandAppended(generate\take($amount));
    }

    public function drop(int $amount): Flow
    {
        return $this->withCommandAppended(generate\drop($amount));
    }

    public function takeWhile(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\takeWhile($predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\takeWhileIndexed($predicate));
    }

    public function dropWhile(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\dropWhile($predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\dropWhileIndexed($predicate));
    }

    public function sortedBy(bool $descending, \Closure $comparator): Flow
    {
        return $this->withCommandAppended(generate\sortedBy($descending, $comparator));
    }

    public function sortBy(\Closure $comparator): Flow
    {
        return $this->withCommandAppended(generate\sortBy($comparator));
    }

    public function sortByDescending(\Closure $comparator): Flow
    {
        return $this->withCommandAppended(generate\sortByDescending($comparator));
    }

}