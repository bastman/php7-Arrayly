<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Arrays\fn;
use Arrayly\Util\internals as utils;

final class Arrayly
{
    /**
     * @var array
     */
    private $data = [];

    public static function ofIterable(iterable $iterable): Arrayly
    {
        return new Arrayly(utils\iterableToArray($iterable));
    }

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toSequence(): Sequence
    {
        return Sequence::ofIterable($this->data);
    }

    public function copy(): Arrayly
    {
        return static::ofIterable($this->data);
    }

    public function withData(array $data): Arrayly
    {
        return static::ofIterable($data);
    }

    public function withKey($key, $value): Arrayly
    {
        $sink = $this->data;
        $sink[$key] = $value;

        return $this->withData($sink);
    }

    public function keys(bool $strict = true): Arrayly
    {
        return $this->withData(fn\keys($this->data, $strict));
    }

    public function values(): Arrayly
    {
        return $this->withData(fn\values($this->data));
    }

    public function flip(): Arrayly
    {
        return $this->withData(fn\flip($this->data));
    }

    public function shuffle(int $times): Arrayly
    {
        return $this->withData(fn\shuffle($this->data, $times));
    }

    public function count(): int
    {
        return fn\count($this->data);
    }

    public function reverse(): Arrayly
    {
        return $this->withData(fn\reverse($this->data));
    }

    public function hasKey($key): bool
    {
        return fn\hasKey($this->data, $key);
    }

    public function firstOrNull()
    {
        return fn\firstOrNull($this->data);
    }

    public function firstOrDefault($defaultValue)
    {
        return fn\firstOrDefault($this->data, $defaultValue);
    }

    public function firstOrElse(\Closure $defaultValueSupplier)
    {
        return fn\firstOrElse($this->data, $defaultValueSupplier);
    }

    public function getOrElse($key, \Closure $defaultValueSupplier)
    {
        return fn\getOrElse($this->data, $key, $defaultValueSupplier);
    }

    public function getOrNull($key)
    {
        return fn\getOrNull($this->data, $key);
    }

    public function getOrDefault($key, $defaultValue)
    {
        return fn\getOrDefault($this->data, $key, $defaultValue);
    }

    public function findOrNull(\Closure $predicate)
    {
        return fn\findOrNull($this->data, $predicate);
    }

    public function findOrDefault(\Closure $predicate, $defaultValue)
    {
        return fn\findOrDefault($this->data, $predicate, $defaultValue);
    }

    public function findOrElse(\Closure $predicate, \Closure $defaultValueSupplier)
    {
        return fn\findOrElse($this->data, $predicate, $defaultValueSupplier);
    }

    public function findIndexedOrNull(\Closure $predicate)
    {
        return fn\findIndexedOrNull($this->data, $predicate);
    }

    public function findIndexedOrDefault(\Closure $predicate, $defaultValue)
    {
        return fn\findIndexedOrDefault($this->data, $predicate, $defaultValue);
    }

    public function findIndexedOrElse(\Closure $predicate, \Closure $defaultValueSupplier)
    {
        return fn\findIndexedOrElse($this->data, $predicate, $defaultValueSupplier);
    }

    public function onEach(\Closure $callback): Arrayly
    {
        fn\onEach($this->data, $callback);

        return $this;
    }

    public function onEachIndexed(\Closure $callback): Arrayly
    {
        fn\onEachIndexed($this->data, $callback);

        return $this;
    }

    public function filter(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\filter($this->data, $predicate));
    }

    public function filterIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\filterIndexed($this->data, $predicate));
    }

    public function filterNot(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\filterNot($this->data, $predicate));
    }

    public function filterNotIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\filterNotIndexed($this->data, $predicate));
    }

    public function filterNotNull(): Arrayly
    {
        return $this->withData(fn\filterNotNull($this->data));
    }

    public function map(\Closure $transform): Arrayly
    {
        return $this->withData(fn\map($this->data, $transform));
    }

    public function mapIndexed(\Closure $transform): Arrayly
    {
        return $this->withData(fn\mapIndexed($this->data, $transform));
    }

    public function mapKeysByValue(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\mapKeysByValue($this->data, $keySelector));
    }

    public function mapKeysByValueIndexed(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\mapKeysByValueIndexed($this->data, $keySelector));
    }

    public function flatMap(\Closure $transform): Arrayly
    {
        return $this->withData(fn\flatMap($this->data, $transform));
    }

    public function flatMapIndexed(\Closure $transform): Arrayly
    {
        return $this->withData(fn\flatMapIndexed($this->data, $transform));
    }

    public function groupBy(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\groupBy($this->data, $keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\groupByIndexed($this->data, $keySelector));
    }

    public function reduce($initialValue, \Closure $reducer)
    {
        return fn\reduce($this->data, $initialValue, $reducer);
    }

    public function reduceIndexed($initialValue, \Closure $reducer)
    {
        return fn\reduceIndexed($this->data, $initialValue, $reducer);
    }

    public function sortedBy(\Closure $comparator, bool $descending): Arrayly
    {
        return $this->withData(fn\sortedBy($this->data, $descending, $comparator));
    }

    public function sortBy(\Closure $comparator): Arrayly
    {
        return $this->withData(fn\sortBy($this->data, $comparator));
    }
    public function sortByDescending(\Closure $comparator): Arrayly
    {
        return $this->withData(fn\sortByDescending($this->data, $comparator));
    }

    public function take(int $amount): Arrayly
    {
        return $this->withData(fn\take($this->data, $amount));
    }

    public function takeWhile(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\takeWhile($this->data, $predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\takeWhileIndexed($this->data, $predicate));
    }
    public function takeLast(int $amount): Arrayly
    {
        return $this->withData(fn\takeLast($this->data, $amount));
    }

    public function drop(int $amount): Arrayly
    {
        return $this->withData(fn\drop($this->data, $amount));
    }

    public function dropWhile(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\dropWhile($this->data, $predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\dropWhileIndexed($this->data, $predicate));
    }

    public function chunk(int $batchSize): Arrayly
    {
        return $this->withData(fn\chunk($this->data, $batchSize));
    }

}