<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Generators\generators as generate;

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
        $sink = [];
        foreach ($this->data as $k => $v) {
            $sink[$k] = $v;
        }

        return $sink;
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
        $gen = generate\reducing($this->data, $initialValue, $reducer);

        return new static($gen);
    }

    public function reducingIndexed($initialValue, \Closure $reducer): Sequence
    {
        $gen = generate\reducingIndexed($this->data, $initialValue, $reducer);

        return new static($gen);
    }

    public function pipeTo(\Closure $transform): Sequence
    {
        $gen = generate\pipeTo($this->data, $transform);

        return new static($gen);
    }

    public function keys(): Sequence
    {
        $gen = generate\keys($this->data);

        return new static($gen);
    }

    public function values(): Sequence
    {
        $gen = generate\values($this->data);

        return new static($gen);
    }

    public function flip(): Sequence
    {
        $gen = generate\flip($this->data);

        return new static($gen);
    }

    public function reverse(bool $preserveKeys): Sequence
    {
        $gen = generate\reverse($this->data, $preserveKeys);

        return new static($gen);
    }

    public function onEach(\Closure $callback): Sequence
    {
        $gen = generate\onEach($this->data, $callback);

        return new static($gen);
    }

    public function onEachIndexed(\Closure $callback): Sequence
    {
        $gen = generate\onEachIndexed($this->data, $callback);

        return new static($gen);
    }

    public function map(\Closure $transform): Sequence
    {
        $gen = generate\map($this->data, $transform);

        return new static($gen);
    }

    public function mapIndexed(\Closure $transform): Sequence
    {
        $gen = generate\mapIndexed($this->data, $transform);

        return new static($gen);
    }

    public function mapKeys(\Closure $keySelector): Sequence
    {
        $gen = generate\mapKeys($this->data, $keySelector);

        return new static($gen);
    }

    public function mapKeysIndexed(\Closure $keySelector): Sequence
    {
        $gen = generate\mapKeysIndexed($this->data, $keySelector);

        return new static($gen);
    }

    public function filter(\Closure $predicate): Sequence
    {
        $gen = generate\filter($this->data, $predicate);

        return new static($gen);
    }

    public function filterIndexed(\Closure $predicate): Sequence
    {
        $gen = generate\filterIndexed($this->data, $predicate);

        return new static($gen);
    }

    public function flatMap(\Closure $transform): Sequence
    {
        $gen = generate\flatMap($this->data, $transform);

        return new static($gen);
    }
    public function flatMapIndexed(\Closure $transform): Sequence
    {
        $gen = generate\flatMapIndexed($this->data, $transform);

        return new static($gen);
    }
    public function groupBy(\Closure $keySelector): Sequence
    {
        $gen = generate\groupBy($this->data, $keySelector);

        return new static($gen);
    }

    public function groupByIndexed(\Closure $keySelector): Sequence
    {
        $gen = generate\groupByIndexed($this->data, $keySelector);

        return new static($gen);
    }

    public function take(int $amount): Sequence
    {
        $gen = generate\take($this->data, $amount);

        return new static($gen);
    }

    public function drop(int $amount): Sequence
    {
        $gen = generate\drop($this->data, $amount);

        return new static($gen);
    }

    public function takeWhile(\Closure $predicate): Sequence
    {
        $gen = generate\takeWhile($this->data, $predicate);

        return new static($gen);
    }

    public function takeWhileIndexed(\Closure $predicate): Sequence
    {
        $gen = generate\takeWhileIndexed($this->data, $predicate);

        return new static($gen);
    }

    public function dropWhile(\Closure $predicate): Sequence
    {
        $gen = generate\dropWhile($this->data, $predicate);

        return new static($gen);
    }

    public function dropWhileIndexed(\Closure $predicate): Sequence
    {
        $gen = generate\dropWhileIndexed($this->data, $predicate);

        return new static($gen);
    }

    public function sortedBy(bool $descending, \Closure $comparator): Sequence
    {
        $gen = generate\sortedBy($this->data, $descending, $comparator);

        return new static($gen);
    }

    public function sortBy(\Closure $comparator): Sequence
    {
        $gen = generate\sortBy($this->data, $comparator);

        return new static($gen);
    }

    public function sortByDescending(\Closure $comparator): Sequence
    {
        $gen = generate\sortByDescending($this->data, $comparator);

        return new static($gen);
    }


}