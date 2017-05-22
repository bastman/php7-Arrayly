<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Generators\generators as generate;
use Arrayly\Util\internals as utils;
class Sequence
{
    /**
     * @var iterable
     */
    private $data;

    public function __construct(iterable $data)
    {
        $this->data = $data;
    }

    public function withData(iterable $data):Sequence {
        return new static($data);
    }

    public static function ofIterable(iterable $data):Sequence {
        return new static($data);
    }

    public static function ofArray(array $source): Sequence
    {
        $gen = function () use ($source) {
            yield from $source;
        };

        return new static($gen());
    }

    public function toArray(): array
    {
        return utils\iterableToArray($this->data);
    }

    public function toArrayly(): Arrayly
    {
        return Arrayly::ofIterable($this->data);
    }

    public function forEachRemaining(\Closure $callback): Void
    {
        foreach ($this->data as $v) {
            $callback($v);
        }
    }

    public function reducing($initialValue, \Closure $reducer): Sequence
    {
        return $this->withData(generate\reducing($this->data, $initialValue, $reducer));
    }

    public function reducingIndexed($initialValue, \Closure $reducer): Sequence
    {
        return $this->withData(generate\reducingIndexed($this->data, $initialValue, $reducer));
    }

    public function pipeTo(\Closure $transform): Sequence
    {
        return $this->withData(generate\pipeTo($this->data, $transform));
    }

    public function keys(): Sequence
    {
        return $this->withData(generate\keys($this->data));
    }

    public function values(): Sequence
    {
        return $this->withData(generate\values($this->data));
    }

    public function flip(): Sequence
    {
        return $this->withData(generate\flip($this->data));
    }

    public function reverse(bool $preserveKeys): Sequence
    {
        return $this->withData(generate\reverse($this->data, $preserveKeys));
    }

    public function onEach(\Closure $callback): Sequence
    {
        return $this->withData(generate\onEach($this->data, $callback));
    }

    public function onEachIndexed(\Closure $callback): Sequence
    {
        return $this->withData(generate\onEachIndexed($this->data, $callback));
    }

    public function map(\Closure $transform): Sequence
    {
        return $this->withData(generate\map($this->data, $transform));
    }

    public function mapIndexed(\Closure $transform): Sequence
    {
        return $this->withData(generate\mapIndexed($this->data, $transform));
    }

    public function mapKeys(\Closure $keySelector): Sequence
    {
        return $this->withData(generate\mapKeys($this->data, $keySelector));
    }

    public function mapKeysIndexed(\Closure $keySelector): Sequence
    {
        return $this->withData(generate\mapKeysIndexed($this->data, $keySelector));
    }

    public function filter(\Closure $predicate): Sequence
    {
        return $this->withData(generate\filter($this->data, $predicate));
    }

    public function filterIndexed(\Closure $predicate): Sequence
    {
        return $this->withData(generate\filterIndexed($this->data, $predicate));
    }

    public function flatMap(\Closure $transform): Sequence
    {
        return $this->withData(generate\flatMap($this->data, $transform));
    }
    public function flatMapIndexed(\Closure $transform): Sequence
    {
        return $this->withData(generate\flatMapIndexed($this->data, $transform));
    }
    public function groupBy(\Closure $keySelector): Sequence
    {
        return $this->withData(generate\groupBy($this->data, $keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): Sequence
    {
        return $this->withData(generate\groupByIndexed($this->data, $keySelector));
    }

    public function take(int $amount): Sequence
    {
        return $this->withData(generate\take($this->data, $amount));
    }

    public function drop(int $amount): Sequence
    {
        return $this->withData(generate\drop($this->data, $amount));
    }

    public function takeWhile(\Closure $predicate): Sequence
    {
        return $this->withData(generate\takeWhile($this->data, $predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): Sequence
    {
        return $this->withData(generate\takeWhileIndexed($this->data, $predicate));
    }

    public function dropWhile(\Closure $predicate): Sequence
    {
        return $this->withData(generate\dropWhile($this->data, $predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): Sequence
    {
        return $this->withData(generate\dropWhileIndexed($this->data, $predicate));
    }

    public function sortedBy(bool $descending, \Closure $comparator): Sequence
    {
        return $this->withData(generate\sortedBy($this->data, $descending, $comparator));
    }

    public function sortBy(\Closure $comparator): Sequence
    {
        return $this->withData(generate\sortBy($this->data, $comparator));
    }

    public function sortByDescending(\Closure $comparator): Sequence
    {
        return $this->withData(generate\sortByDescending($this->data, $comparator));
    }


}