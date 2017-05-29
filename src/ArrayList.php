<?php
declare(strict_types=1);

namespace Arrayly;

use Arrayly\Util\internals as utils;
use Arrayly\Arrays\fn as arrays;

final class ArrayList implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $data = [];

    public static function ofIterable(iterable $iterable): self {
        return new static(utils\iterableToArray($iterable));
    }

    private function __construct(array $data) {
        $this->data = array_values($data);
    }

    public function collect(): Sink {
        return Sink::ofArray($this->toArray());
    }

    public function toArray(): array {
        return array_values($this->data);
    }

    public function getIterator(): \Generator {
        yield from $this->data;
    }

    public function copy(): self {
        return static::ofIterable($this->toArray());
    }

    public function withData(array $data): self {
        return static::ofIterable($data);
    }


    public function keys(bool $strict = true): self {
        return $this->withData(arrays\keys($this->data, $strict));
    }

    public function values(): self {
        return $this->withData(arrays\values($this->data));
    }

    public function flip(): self {
        return $this->withData(arrays\flip($this->data));
    }

    public function shuffle(int $times): self {
        return $this->withData(arrays\shuffle($this->data, $times));
    }

    public function count(): int {
        return arrays\count($this->data);
    }

    public function reverse(): self {
        return $this->withData(arrays\reverse($this->data));
    }

    public function hasElementAt(int $index): bool {
        return arrays\hasKey($this->data, $index);
    }

    public function firstOrNull() {
        return arrays\firstOrNull($this->data);
    }

    public function firstOrDefault($defaultValue) {
        return arrays\firstOrDefault($this->data, $defaultValue);
    }

    public function firstOrElse(\Closure $defaultValueSupplier) {
        return arrays\firstOrElse($this->data, $defaultValueSupplier);
    }

    public function getOrElse(int $index, \Closure $defaultValueSupplier) {
        return arrays\getOrElse($this->data, $index, $defaultValueSupplier);
    }

    public function getOrNull(int $index) {
        return arrays\getOrNull($this->data, $index);
    }

    public function getOrDefault(int $index, $defaultValue) {
        return arrays\getOrDefault($this->data, $index, $defaultValue);
    }

    public function findOrNull(\Closure $predicate) {
        return arrays\findOrNull($this->data, $predicate);
    }

    public function findOrDefault(\Closure $predicate, $defaultValue) {
        return arrays\findOrDefault($this->data, $predicate, $defaultValue);
    }

    public function findOrElse(\Closure $predicate, \Closure $defaultValueSupplier) {
        return arrays\findOrElse($this->data, $predicate, $defaultValueSupplier);
    }

    public function findIndexedOrNull(\Closure $predicate) {
        return arrays\findIndexedOrNull($this->data, $predicate);
    }

    public function findIndexedOrDefault(\Closure $predicate, $defaultValue) {
        return arrays\findIndexedOrDefault($this->data, $predicate, $defaultValue);
    }

    public function findIndexedOrElse(\Closure $predicate, \Closure $defaultValueSupplier) {
        return arrays\findIndexedOrElse($this->data, $predicate, $defaultValueSupplier);
    }

    public function onEach(\Closure $callback): self {
        arrays\onEach($this->data, $callback);

        return $this;
    }

    public function onEachIndexed(\Closure $callback): self {
        arrays\onEachIndexed($this->data, $callback);

        return $this;
    }

    public function filter(\Closure $predicate): self {
        return $this->withData(arrays\filter($this->data, $predicate));
    }

    public function filterIndexed(\Closure $predicate): self {
        return $this->withData(arrays\filterIndexed($this->data, $predicate));
    }

    public function filterNot(\Closure $predicate): self {
        return $this->withData(arrays\filterNot($this->data, $predicate));
    }

    public function filterNotIndexed(\Closure $predicate): self {
        return $this->withData(arrays\filterNotIndexed($this->data, $predicate));
    }

    public function filterNotNull(): self {
        return $this->withData(arrays\filterNotNull($this->data));
    }

    public function map(\Closure $transform): self {
        return $this->withData(arrays\map($this->data, $transform));
    }

    public function mapIndexed(\Closure $transform): self {
        return $this->withData(arrays\mapIndexed($this->data, $transform));
    }

    public function mapKeysByValue(\Closure $keySelector): self {
        return $this->withData(arrays\mapKeysByValue($this->data, $keySelector));
    }

    public function mapKeysByValueIndexed(\Closure $keySelector): self {
        return $this->withData(arrays\mapKeysByValueIndexed($this->data, $keySelector));
    }

    public function flatMap(\Closure $transform): self {
        return $this->withData(arrays\flatMap($this->data, $transform));
    }

    public function flatMapIndexed(\Closure $transform): self {
        return $this->withData(arrays\flatMapIndexed($this->data, $transform));
    }

    public function groupBy(\Closure $keySelector): Arrayly {
        return Arrayly::ofIterable(arrays\groupBy($this->data, $keySelector));
    }

    public function groupByIndexed(\Closure $keySelector): Arrayly {
        return Arrayly::ofIterable(arrays\groupByIndexed($this->data, $keySelector));
    }

    public function reduce($initialValue, \Closure $reducer) {
        return arrays\reduce($this->data, $initialValue, $reducer);
    }

    public function reduceIndexed($initialValue, \Closure $reducer) {
        return arrays\reduceIndexed($this->data, $initialValue, $reducer);
    }

    public function sortedBy(\Closure $comparator, bool $descending): ArrayList {
        return $this->withData(arrays\sortedBy($this->data, $descending, $comparator));
    }

    public function sortBy(\Closure $comparator): ArrayList {
        return $this->withData(arrays\sortBy($this->data, $comparator));
    }

    public function sortByDescending(\Closure $comparator): ArrayList {
        return $this->withData(arrays\sortByDescending($this->data, $comparator));
    }

    public function take(int $amount): ArrayList {
        return $this->withData(arrays\take($this->data, $amount));
    }

    public function takeWhile(\Closure $predicate): ArrayList {
        return $this->withData(arrays\takeWhile($this->data, $predicate));
    }

    public function takeWhileIndexed(\Closure $predicate): ArrayList {
        return $this->withData(arrays\takeWhileIndexed($this->data, $predicate));
    }

    public function takeLast(int $amount): ArrayList {
        return $this->withData(arrays\takeLast($this->data, $amount));
    }

    public function drop(int $amount): ArrayList {
        return $this->withData(arrays\drop($this->data, $amount));
    }

    public function dropWhile(\Closure $predicate): ArrayList {
        return $this->withData(arrays\dropWhile($this->data, $predicate));
    }

    public function dropWhileIndexed(\Closure $predicate): ArrayList {
        return $this->withData(arrays\dropWhileIndexed($this->data, $predicate));
    }

    public function chunk(int $batchSize): ArrayList {
        return $this->withData(arrays\chunk($this->data, $batchSize));
    }

    public function nth(int $n): ArrayList {
        return $this->withData(arrays\nth($this->data, $n));
    }

    public function slice(?int $startIndex, ?int $stopIndexExclusive, int $step=1): ArrayList {
        return $this->withData(arrays\slice($this->data, $startIndex, $stopIndexExclusive, $step));
    }
    public function sliceByOffsetAndLimit(int $offset, ?int $limit, int $step=1): ArrayList {
        return $this->withData(arrays\sliceByOffsetAndLimit($this->data, $offset, $limit, $step));
    }

}