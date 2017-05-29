<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Producers\RewindableProducer;

use Arrayly\Generators\partials as generate;
use Arrayly\Util\internals as utils;

final class Flow
{
    /**
     * @var \Closure[]
     */
    private $commands = [];

    /**
     * @var RewindableProducer
     */
    private $producer;

    public static function create():self {
        return new static(RewindableProducer::ofIterable([]), []);
    }

    public static function ofIterable(iterable $iterable):self {
        return static::create()
            ->withProducerOfIterable($iterable);
    }
    public static function ofRewindableIteratorSupplier(\Closure $supplier):self {
        return static::create()
            ->withProducerOfIteratorSupplier($supplier);
    }

    private function __construct(RewindableProducer $producer, array $commands) {
        $this->commands = utils\requireClosureListFromVarArgs(...$commands);
        $this->producer=$producer;
    }

    public function copy():self {
        return new static($this->producer, $this->commands);
    }

    public function withoutProducer():self {
        return new static(RewindableProducer::ofIterable([]), $this->commands);
    }

    public function withProducer(RewindableProducer $producer):self {
        return new static($producer, $this->commands);
    }

    public function withProducerOfIterable(iterable $iterable):self {
        return $this->withProducer(RewindableProducer::ofIterable($iterable));
    }

    public function withProducerOfIteratorSupplier(\Closure $iteratorSupplier):self {
        return $this->withProducer(RewindableProducer::ofIteratorSupplier($iteratorSupplier));
    }

    private function withCommandAppended(\Closure ...$commands):self {
        $newInstance = $this->copy();
        $newInstance->commands = utils\appendClosureToList(
            $newInstance->commands,
            ...$commands
        );

        return $newInstance;
    }

    private function run(): iterable {
        // work on a fresh (rewinded) iterator source
        $iterable=$this->producer->newInstance();
        foreach ($this->commands as $c) {
            $iterable = $c($iterable);
        }

        return $iterable;
    }

    public function collect():Sink {
        $iter = $this->run();
        $sink=[];
        foreach ($iter as $k=>$v) {
            $sink[$k] = $v;
        }

        return Sink::ofArray($sink);
    }


    public function reducing($initialValue, \Closure $reducer): self {
        return $this->withCommandAppended(generate\reducing($initialValue, $reducer));
    }

    public function reducingIndexed($initialValue, \Closure $reducer): self {
        return $this->withCommandAppended(generate\reducingIndexed($initialValue, $reducer));
    }

    public function pipe(\Closure $transform): self {
        return $this->withCommandAppended(generate\pipe($transform));
    }

    public function keys(): self {
        return $this->withCommandAppended(generate\keys());
    }

    public function values(): self {
        return $this->withCommandAppended(generate\values());
    }

    public function flip(): self {
        return $this->withCommandAppended(generate\flip());
    }

    public function reverse(): self {
        return $this->withCommandAppended(generate\reverse());
    }

    public function onEach(\Closure $callback): self {
        return $this->withCommandAppended(generate\onEach($callback));
    }

    public function onEachIndexed(\Closure $callback): self {
        return $this->withCommandAppended(generate\onEachIndexed($callback));
    }

    public function map(\Closure $transform): self {
        return $this->withCommandAppended(generate\map($transform));
    }

    public function mapIndexed(\Closure $transform): self {
        return $this->withCommandAppended(generate\mapIndexed($transform));
    }

    public function mapKeysByValue(\Closure $keySelector): self {
        return $this->withCommandAppended(generate\mapKeysByValue($keySelector));
    }

    public function mapKeysByValueIndexed(\Closure $keySelector): self {
        return $this->withCommandAppended(generate\mapKeysByValueIndexed($keySelector));
    }

    public function filter(\Closure $predicate): self {
        return $this->withCommandAppended(generate\filter($predicate));
    }

    public function filterIndexed(\Closure $predicate): self {
        return $this->withCommandAppended(generate\filterIndexed($predicate));
    }

    public function filterNot(\Closure $predicate): self {
        return $this->withCommandAppended(generate\filterNot($predicate));
    }

    public function filterNotIndexed(\Closure $predicate): self {
        return $this->withCommandAppended(generate\filterNotIndexed($predicate));
    }

    public function filterNotNull(): self {
        return $this->withCommandAppended(generate\filterNotNull());
    }

    public function flatMap(\Closure $transform): self {
        return $this->withCommandAppended(generate\flatMap($transform));
    }
    public function flatMapIndexed(\Closure $transform): self {
        return $this->withCommandAppended(generate\flatMapIndexed($transform));
    }
    public function groupBy(\Closure $keySelector): self {
        return $this->withCommandAppended(generate\groupBy($keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): self {
        return $this->withCommandAppended(generate\groupByIndexed($keySelector));
    }

    public function take(int $amount): self {
        return $this->withCommandAppended(generate\take($amount));
    }

    public function takeWhile(\Closure $predicate): self {
        return $this->withCommandAppended(generate\takeWhile($predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): self {
        return $this->withCommandAppended(generate\takeWhileIndexed($predicate));
    }

    public function takeLast(int $amount): self {
        return $this->withCommandAppended(generate\takeLast($amount));
    }

    public function drop(int $amount): self {
        return $this->withCommandAppended(generate\drop($amount));
    }

    public function dropWhile(\Closure $predicate): self {
        return $this->withCommandAppended(generate\dropWhile($predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): self {
        return $this->withCommandAppended(generate\dropWhileIndexed($predicate));
    }

    public function sortedBy(bool $descending, \Closure $comparator): self {
        return $this->withCommandAppended(generate\sortedBy($descending, $comparator));
    }

    public function sortBy(\Closure $comparator): self {
        return $this->withCommandAppended(generate\sortBy($comparator));
    }

    public function sortByDescending(\Closure $comparator): self {
        return $this->withCommandAppended(generate\sortByDescending($comparator));
    }

    public function chunk(int $batchSize): self {
        return $this->withCommandAppended(generate\chunk($batchSize));
    }

    public function nth(int $n): self {
        return $this->withCommandAppended(generate\nth($n));
    }

    public function slice(?int $startIndex, ?int $stopIndexExclusive, int $step=1): self {
        return $this->withCommandAppended(generate\slice($startIndex, $stopIndexExclusive, $step));
    }

    public function sliceByOffsetAndLimit(int $offset, ?int $limit, int $step=1): self {
        return $this->withCommandAppended(generate\sliceByOffsetAndLimit($offset, $limit, $step));
    }
}