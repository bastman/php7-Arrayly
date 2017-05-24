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

    public static function create():Flow {
        return new static(RewindableProducer::ofIterable([]), []);
    }

    public static function ofIterable(iterable $iterable):Flow {
        return static::create()
            ->withProducerOfIterable($iterable);
    }

    private function __construct(RewindableProducer $producer, array $commands)
    {
        $this->commands = utils\requireClosureListFromVarArgs(...$commands);
        $this->producer=$producer;
    }

    public function copy():Flow {
        return new static($this->producer, $this->commands);
    }

    public function withoutProducer():Flow {
        return new static(RewindableProducer::ofIterable([]), $this->commands);
    }
    public function withProducer(RewindableProducer $producer):Flow {
        return new static($producer, $this->commands);
    }
    public function withProducerOfIterable(iterable $iterable):Flow {
        return $this->withProducer(RewindableProducer::ofIterable($iterable));
    }
    public function withProducerOfIteratorSupplier(\Closure $iteratorSupplier):Flow {
        return $this->withProducer(RewindableProducer::ofIteratorSupplier($iteratorSupplier));
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

    public function reverse(): Flow
    {
        return $this->withCommandAppended(generate\reverse());
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

    public function mapKeysByValue(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(generate\mapKeysByValue($keySelector));
    }

    public function mapKeysByValueIndexed(\Closure $keySelector): Flow
    {
        return $this->withCommandAppended(generate\mapKeysByValueIndexed($keySelector));
    }

    public function filter(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\filter($predicate));
    }

    public function filterIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\filterIndexed($predicate));
    }

    public function filterNot(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\filterNot($predicate));
    }

    public function filterNotIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\filterNotIndexed($predicate));
    }

    public function filterNotNull(): Flow
    {
        return $this->withCommandAppended(generate\filterNotNull());
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

    public function takeWhile(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\takeWhile($predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): Flow
    {
        return $this->withCommandAppended(generate\takeWhileIndexed($predicate));
    }

    public function takeLast(int $amount): Flow
    {
        return $this->withCommandAppended(generate\takeLast($amount));
    }

    public function drop(int $amount): Flow
    {
        return $this->withCommandAppended(generate\drop($amount));
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

    public function chunk(int $batchSize): Flow
    {
        return $this->withCommandAppended(generate\chunk($batchSize));
    }

    public function slice(int $offset, ?int $length): Flow
    {
        return $this->withCommandAppended(generate\slice($offset, $length));
    }

}