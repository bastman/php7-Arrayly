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

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return Arrayly
     */
    public function copy(): Arrayly
    {
        return static::ofIterable($this->data);
    }

    /**
     * @param array $data
     * @return Arrayly
     */
    public function withData(array $data): Arrayly
    {
        return static::ofIterable($data);
    }

    /**
     * @return Sequence
     */
    public function asSequence(): Sequence
    {
        return Sequence::ofIterable($this->data);
    }

    /**
     * @param $key
     * @param $value
     * @return Arrayly
     */
    public function withKey($key, $value): Arrayly
    {
        $sink = $this->data;
        $sink[$key] = $value;

        return $this->withData($sink);
    }

    /**
     * @param bool $strict
     * @return Arrayly
     */
    public function keys(bool $strict = true): Arrayly
    {
        return $this->withData(fn\keys($this->data, $strict));
    }

    /**
     * @return Arrayly
     */
    public function values(): Arrayly
    {
        return $this->withData(fn\values($this->data));
    }

    /**
     * @return Arrayly
     */
    public function flip(): Arrayly
    {
        return $this->withData(fn\flip($this->data));
    }

    /**
     * @param int $times
     * @return Arrayly
     */
    public function shuffle(int $times): Arrayly
    {
        return $this->withData(fn\shuffle($this->data, $times));
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return fn\count($this->data);
    }

    /**
     * @param bool $preserveKeys
     * @return Arrayly
     */
    public function reverse(bool $preserveKeys): Arrayly
    {
        return $this->withData(fn\reverse($this->data, $preserveKeys));
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key): bool
    {
        return fn\hasKey($this->data, $key);
    }

    /**
     * @return mixed|null
     */
    public function firstOrNull()
    {
        return fn\firstOrNull($this->data);
    }

    /**
     * @param $defaultValue
     * @return mixed
     */
    public function firstOrDefault($defaultValue)
    {
        return fn\firstOrDefault($this->data, $defaultValue);
    }

    /**
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function firstOrElse(\Closure $defaultValueSupplier)
    {
        return fn\firstOrElse($this->data, $defaultValueSupplier);
    }

    /**
     * @param $key
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function getOrElse($key, \Closure $defaultValueSupplier)
    {
        return fn\getOrElse($this->data, $key, $defaultValueSupplier);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getOrNull($key)
    {
        return fn\getOrNull($this->data, $key);
    }

    /**
     * @param $key
     * @param $defaultValue
     * @return mixed
     */
    public function getOrDefault($key, $defaultValue)
    {
        return fn\getOrDefault($this->data, $key, $defaultValue);
    }

    /**
     * @param \Closure $predicate
     * @return mixed
     */
    public function findOrNull(\Closure $predicate)
    {
        return fn\findOrNull($this->data, $predicate);
    }

    /**
     * @param \Closure $predicate
     * @param $defaultValue
     * @return mixed
     */
    public function findOrDefault(\Closure $predicate, $defaultValue)
    {
        return fn\findOrDefault($this->data, $predicate, $defaultValue);
    }

    /**
     * @param \Closure $predicate
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function findOrElse(\Closure $predicate, \Closure $defaultValueSupplier)
    {
        return fn\findOrElse($this->data, $predicate, $defaultValueSupplier);
    }

    /**
     * @param \Closure $predicate
     * @return mixed
     */
    public function findIndexedOrNull(\Closure $predicate)
    {
        return fn\findIndexedOrNull($this->data, $predicate);
    }

    /**
     * @param \Closure $predicate
     * @param $defaultValue
     * @return mixed
     */
    public function findIndexedOrDefault(\Closure $predicate, $defaultValue)
    {
        return fn\findIndexedOrDefault($this->data, $predicate, $defaultValue);
    }

    /**
     * @param \Closure $predicate
     * @param \Closure $defaultValueSupplier
     * @return mixed
     */
    public function findIndexedOrElse(\Closure $predicate, \Closure $defaultValueSupplier)
    {
        return fn\findIndexedOrElse($this->data, $predicate, $defaultValueSupplier);
    }

    /**
     * @param \Closure $callback
     * @return Arrayly
     */
    public function onEach(\Closure $callback): Arrayly
    {
        fn\onEach($this->data, $callback);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return Arrayly
     */
    public function onEachIndexed(\Closure $callback): Arrayly
    {
        fn\onEachIndexed($this->data, $callback);

        return $this;
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function filter(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\filter($this->data, $predicate));
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function filterIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\filterIndexed($this->data, $predicate));
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function map(\Closure $transform): Arrayly
    {
        return $this->withData(fn\map($this->data, $transform));
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function mapIndexed(\Closure $transform): Arrayly
    {
        return $this->withData(fn\mapIndexed($this->data, $transform));
    }

    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function mapKeysByValue(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\mapKeysByValue($this->data, $keySelector));
    }

    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function mapKeysByValueIndexed(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\mapKeysByValueIndexed($this->data, $keySelector));
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function flatMap(\Closure $transform): Arrayly
    {
        return $this->withData(fn\flatMap($this->data, $transform));
    }

    /**
     * @param \Closure $transform
     * @return Arrayly
     */
    public function flatMapIndexed(\Closure $transform): Arrayly
    {
        return $this->withData(fn\flatMapIndexed($this->data, $transform));
    }


    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function groupBy(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\groupBy($this->data, $keySelector));
    }

    /**
     * @param \Closure $keySelector
     * @return Arrayly
     */
    public function groupByIndexed(\Closure $keySelector): Arrayly
    {
        return $this->withData(fn\groupByIndexed($this->data, $keySelector));
    }

    /**
     * @param mixed $initialValue
     * @param \Closure $reducer
     * @return mixed
     */
    public function reduce($initialValue, \Closure $reducer)
    {
        return fn\reduce($this->data, $initialValue, $reducer);
    }

    /**
     * @param mixed $initialValue
     * @param \Closure $reducer
     * @return mixed
     */
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

    /**
     * @param int $amount
     * @return Arrayly
     */
    public function take(int $amount): Arrayly
    {
        return $this->withData(fn\take($this->data, $amount));
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function takeWhile(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\takeWhile($this->data, $predicate));
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function takeWhileIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\takeWhileIndexed($this->data, $predicate));
    }

    /**
     * @param int $amount
     * @return Arrayly
     */
    public function drop(int $amount): Arrayly
    {
        return $this->withData(fn\drop($this->data, $amount));
    }


    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function dropWhile(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\dropWhile($this->data, $predicate));
    }

    /**
     * @param \Closure $predicate
     * @return Arrayly
     */
    public function dropWhileIndexed(\Closure $predicate): Arrayly
    {
        return $this->withData(fn\dropWhileIndexed($this->data, $predicate));
    }

}