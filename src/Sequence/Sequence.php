<?php
declare(strict_types=1);

namespace Arrayly\Sequence;

use Arrayly\Arrayly;
use Arrayly\Sequence\generators as generate;

class Sequence
{
    /**
     * @var \Generator
     */
    private $data;

    public function __construct(\Generator $data)
    {
        $this->data = $data;
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

    /**
     * @param mixed $initialValue
     * @param \Closure $reducer
     * @return mixed
     */
    public function reduce($initialValue, \Closure $reducer)
    {
        $accumulatedValue = $initialValue;
        foreach ($this->data as $k => $v) {
            $accumulatedValue = $reducer($accumulatedValue, $v);
        }

        return $accumulatedValue;
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

    public function sortBy(\Closure $comparator, bool $descending): Sequence
    {
        $gen = generate\sortBy($this->data, $comparator, $descending);

        return new static($gen);
    }


}