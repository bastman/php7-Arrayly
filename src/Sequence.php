<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Generators\generators as generate;
use Arrayly\Util\internals as utils;

final class Sequence
{
    /**
     * @var iterable
     */
    private $data;

    public static function ofIterable(iterable $source): self {
        return new static($source);
    }

    public static function ofIteratorSupplier(\Closure $supplier): self {
        return static::ofIterable(utils\iteratorSupplierToIterator($supplier));
    }

    private function __construct(iterable $data) {
        $this->data = $data;
    }

    public function withData(iterable $data):self {
        return new static($data);
    }

    public function collect():Sink {
        return Sink::ofArray(utils\iterableToArray($this->data));
    }

    public function forEachRemaining(\Closure $callback): Void {
        foreach ($this->data as $v) {
            $callback($v);
        }
    }

    public function reducing($initialValue, \Closure $reducer): self {
        return $this->withData(generate\reducing($this->data, $initialValue, $reducer));
    }

    public function reducingIndexed($initialValue, \Closure $reducer): self {
        return $this->withData(generate\reducingIndexed($this->data, $initialValue, $reducer));
    }

    public function pipe(\Closure $transform): self {
        return $this->withData(generate\pipe($this->data, $transform));
    }

    public function keys(): self {
        return $this->withData(generate\keys($this->data));
    }

    public function values(): self {
        return $this->withData(generate\values($this->data));
    }

    public function flip(): self {
        return $this->withData(generate\flip($this->data));
    }

    public function reverse(): self {
        return $this->withData(generate\reverse($this->data));
    }

    public function onEach(\Closure $callback): self {
        return $this->withData(generate\onEach($this->data, $callback));
    }

    public function onEachIndexed(\Closure $callback): self {
        return $this->withData(generate\onEachIndexed($this->data, $callback));
    }

    public function map(\Closure $transform): self {
        return $this->withData(generate\map($this->data, $transform));
    }

    public function mapIndexed(\Closure $transform): self {
        return $this->withData(generate\mapIndexed($this->data, $transform));
    }

    public function mapKeysByValue(\Closure $keySelector): self {
        return $this->withData(generate\mapKeysByValue($this->data, $keySelector));
    }

    public function mapKeysByValueIndexed(\Closure $keySelector): self {
        return $this->withData(generate\mapKeysByValueIndexed($this->data, $keySelector));
    }

    public function filter(\Closure $predicate): self
    {
        return $this->withData(generate\filter($this->data, $predicate));
    }

    public function filterIndexed(\Closure $predicate): self {
        return $this->withData(generate\filterIndexed($this->data, $predicate));
    }

    public function filterNot(\Closure $predicate): self {
        return $this->withData(generate\filterNot($this->data, $predicate));
    }

    public function filterNotIndexed(\Closure $predicate): self {
        return $this->withData(generate\filterNotIndexed($this->data, $predicate));
    }

    public function filterNotNull(): self {
        return $this->withData(generate\filterNotNull($this->data));
    }

    public function flatMap(\Closure $transform): self {
        return $this->withData(generate\flatMap($this->data, $transform));
    }

    public function flatMapIndexed(\Closure $transform): self
    {
        return $this->withData(generate\flatMapIndexed($this->data, $transform));
    }
    public function groupBy(\Closure $keySelector): self {
        return $this->withData(generate\groupBy($this->data, $keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): self {
        return $this->withData(generate\groupByIndexed($this->data, $keySelector));
    }

    public function take(int $amount): self {
        return $this->withData(generate\take($this->data, $amount));
    }

    public function takeWhile(\Closure $predicate): self {
        return $this->withData(generate\takeWhile($this->data, $predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): self {
        return $this->withData(generate\takeWhileIndexed($this->data, $predicate));
    }

    public function takeLast(int $amount): self {
        return $this->withData(generate\takeLast($this->data, $amount));
    }

    public function drop(int $amount): self {
        return $this->withData(generate\drop($this->data, $amount));
    }

    public function dropWhile(\Closure $predicate): self {
        return $this->withData(generate\dropWhile($this->data, $predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): self {
        return $this->withData(generate\dropWhileIndexed($this->data, $predicate));
    }

    public function sortedBy(bool $descending, \Closure $comparator): self {
        return $this->withData(generate\sortedBy($this->data, $descending, $comparator));
    }

    public function sortBy(\Closure $comparator): self {
        return $this->withData(generate\sortBy($this->data, $comparator));
    }

    public function sortByDescending(\Closure $comparator): self {
        return $this->withData(generate\sortByDescending($this->data, $comparator));
    }

    public function chunk(int $batchSize): self {
        return $this->withData(generate\chunk($this->data, $batchSize));
    }

    public function slice(?int $startIndex, ?int $stopIndexExclusive, int $step=1): self {
        return $this->withData(generate\slice($this->data, $startIndex, $stopIndexExclusive, $step));
    }
    public function sliceByOffsetAndLimit(int $offset, ?int $limit, int $step=1): self {
        return $this->withData(generate\sliceByOffsetAndLimit($this->data, $offset, $limit, $step));
    }
}