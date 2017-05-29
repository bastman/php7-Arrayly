# php7-Arrayly
- ArrayMap (eager): decorates php array with immutable HashMap-style methods similar to Java Streams / Kotlin Collections
- ArrayList (eager): decorates php array_values($array) with immutable List-style methods
- Sequence (lazy-ish, consume-once): provides fluid interface to php generators & iterators
- Flow (lazy-ish, consume-rewindable, FBP): kind of flow-based-programming-style (FBP) for replayable transformation pipelines


inspired by 
- nikic/iter: https://github.com/nikic/iter
- Kotlin Collections: https://antonioleiva.com/collection-operations-kotlin/
- Java Stream API: http://winterbe.com/posts/2014/07/31/java8-stream-tutorial-examples/
- Stringy (OO-Style decorator for strings): https://github.com/danielstjules/Stringy

## Alternative Concepts
- Transducers: https://github.com/mtdowling/transducers.php
- Pipeline, e.g.: https://www.hughgrigg.com/posts/simple-pipes-php-generators
## Notes
- Experimental.

## Design Principles
- functional programming
- immutability
- strictly strong typing
- composition over inheritance hell
- treat php arrays as kind of Map (-vs- List), therefore try to preserve keys

## Methods
 - filter, map, flatMap, reduce, groupBy, find, sort, chunk, take, drop, ...
 
## Install
    $ composer require bastman/php7-arrayly 0.1.0

## Examples (ArrayMap)
- see: tests/examples/arrayly

            mapOfIterable($cities)
            
            ->map(function ($item) {return $item["country"];})
            ->filter(function ($country) {return $country == 'Germany';})
            ->sortByDescending(function ($a, $b) {return strcasecmp($a, $b);})
            ->drop(1)
            ->take(2)
            
            ->toArray();
-             
            mapOfIterable($cities)
                        ->groupBy(function (array $item):string {
                            return $item["country"];
                        })
                        ->flatMap(function (array $itemGroup):array {
                            return $itemGroup;
                        })
                        ->reverse()
                        ->reduce('', function (string $acc, array $item):string {
                            return $acc . ':' . $item["city"];
                        });
                        
## Examples (ArrayList)

- 
            listOf("a1", "a2", "b1", "a3")
            
            ->map(function ($value) {return strtoupper($value);})
            ->filter(function ($value) {return fnmatch("*A*", $value);})
            ->sortByDescending(function ($a, $b) {return strcasecmp($a, $b);})
            ->drop(1)
            ->take(2)
            
            ->toArray();
-             
            listOfIterable($cities)
            
                        ->groupBy(function (array $item):string {
                            return $item["country"];
                        })
                        ->flatMap(function (array $itemGroup):array {
                            return $itemGroup;
                        })
                        ->reverse()
                        ->reduce('', function (string $acc, array $item):string {
                            return $acc . ':' . $item["city"];
                        });                        
                        
## Examples (Sequence)  - uses generators approach           
- see: tests/examples/sequence

             sequenceOfIterable($cities)
             
              ->filter(function (array $v):bool {
                  return $v['country']==='Germany';
              })
              ->map(function(array $v):array{
                  return $v;
              })
              ->groupBy(function (array $v):string {
                  return $v['country'];
              })
              ->flatMap(function (array $itemGroup):array {
                  return $itemGroup;
              })
              ->pipe(function(iterable $iterable){
                  foreach ($iterable as $k=>$v) {
                      yield $k=>$v;
                  }
              })
              
              ->collect()
              ->toArray();
              
## Examples (Flow)  - Flow Based Programming (FBP)           
- see: tests/examples/flow
1.
        // define the re-usable flow
        $flow = Flow::create()
            ->filter(function (array $v): bool {
                return $v['country'] === 'Germany';
            })
            ->map(function (array $v): array {
                return $v;
            })
            ->groupBy(function (array $v): string {
                return $v['country'];
            })
            ->flatMap(function (array $itemGroup): array {
                return $itemGroup;
            });
2.
        // run the flow with a given producer
        $cities = self::createCities();
        $sink = $flow->withProducerOfIterable($cities)
            ->collect()
            ->toArray();
        
3.
        // derive a new flow by applying a different producer
        // note: the old flow will not be affected by this. immutability rockz :)
        
        $derivedFlow = $flow->withProducerOfIteratorSupplier(
                function():\Generator { 
                    yield from self::createCities();
                });
4.      
        // run the derived flow        
        $sink = $derivedFlow
            ->collect()
            ->toArray();
5.
        // and re-run the derived flow - because we can ;)
        $sink = $derivedFlow
            ->collect()
            ->toArray();

## Api (Source: top-level-functions)

    function listOf(...$values):ArrayList
    function listOfIterable(iterable $iterable):ArrayList
    function mapOfIterable(iterable $iterable):ArrayMap
    function sequenceOfIterable(iterable $iterable):Sequence
    function sequenceOfIteratorSupplier(\Closure $supplier):Sequence
    function sequenceOfRewindableIteratorSupplier(\Closure $supplier):Sequence

## Api (Sink)

    public function toArray(): array;

    public function toGenerator(): \Generator;

    public function toSequence(): Sequence;

    public function toMap(): ArrayMap;

    public function toList(): ArrayList;

    public function getIterator(): \Generator;

    public function toIteratorSupplier(): \Closure;

## Api (ArrayMap)

    public function collect(): Sink;

    public function toArray(): array;

    public function getIterator(): \Generator;

    public function copy(): ArrayMap;

    public function withData(array $data): ArrayMap;

    public function withKey($key, $value): ArrayMap;

    public function keys(bool $strict = true): ArrayMap;

    public function values(): ArrayMap;

    public function flip(): ArrayMap;

    public function shuffle(int $times): ArrayMap;

    public function count(): int;

    public function reverse(): ArrayMap;

    public function hasKey($key): bool;

    public function firstOrNull();

    public function firstOrDefault($defaultValue);

    public function firstOrElse(\Closure $defaultValueSupplier);

    public function getOrElse($key, \Closure $defaultValueSupplier);

    public function getOrNull($key);

    public function getOrDefault($key, $defaultValue);

    public function findOrNull(\Closure $predicate);

    public function findOrDefault(\Closure $predicate, $defaultValue);

    public function findOrElse(\Closure $predicate, \Closure $defaultValueSupplier);

    public function findIndexedOrNull(\Closure $predicate);

    public function findIndexedOrDefault(\Closure $predicate, $defaultValue);

    public function findIndexedOrElse(\Closure $predicate, \Closure $defaultValueSupplier);

    public function onEach(\Closure $callback): ArrayMap;

    public function onEachIndexed(\Closure $callback): ArrayMap;

    public function filter(\Closure $predicate): ArrayMap;

    public function filterIndexed(\Closure $predicate): ArrayMap;

    public function filterNot(\Closure $predicate): ArrayMap;

    public function filterNotIndexed(\Closure $predicate): ArrayMap;

    public function filterNotNull(): ArrayMap;

    public function map(\Closure $transform): ArrayMap;

    public function mapIndexed(\Closure $transform): ArrayMap;

    public function mapKeysByValue(\Closure $keySelector): ArrayMap;

    public function mapKeysByValueIndexed(\Closure $keySelector): ArrayMap;

    public function flatMap(\Closure $transform): ArrayMap;

    public function flatMapIndexed(\Closure $transform): ArrayMap;

    public function groupBy(\Closure $keySelector): ArrayMap;

    public function groupByIndexed(\Closure $keySelector): ArrayMap;

    public function reduce($initialValue, \Closure $reducer);

    public function reduceIndexed($initialValue, \Closure $reducer);

    public function sortedBy(\Closure $comparator, bool $descending): ArrayMap;

    public function sortBy(\Closure $comparator): ArrayMap;

    public function sortByDescending(\Closure $comparator): ArrayMap;

    public function take(int $amount): ArrayMap;

    public function takeWhile(\Closure $predicate): ArrayMap;

    public function takeWhileIndexed(\Closure $predicate): ArrayMap;

    public function takeLast(int $amount): ArrayMap;

    public function drop(int $amount): ArrayMap;

    public function dropWhile(\Closure $predicate): ArrayMap;

    public function dropWhileIndexed(\Closure $predicate): ArrayMap;

    public function chunk(int $batchSize): ArrayMap;

    public function nth(int $n): ArrayMap;

    public function slice(? $startIndex, ? $stopIndexExclusive, int $step = 1): ArrayMap;

    public function sliceByOffsetAndLimit(int $offset, ? $limit, int $step = 1): ArrayMap;
  
## Api (ArrayList)

    public function collect(): Sink;

    public function toArray(): array;

    public function getIterator(): \Generator;

    public function copy(): ArrayList;

    public function withData(array $data): ArrayList;

    public function keys(bool $strict = true): ArrayList;

    public function values(): ArrayList;

    public function flip(): ArrayList;

    public function shuffle(int $times): ArrayList;

    public function count(): int;

    public function reverse(): ArrayList;

    public function hasElementAt(int $index): bool;

    public function firstOrNull();

    public function firstOrDefault($defaultValue);

    public function firstOrElse(\Closure $defaultValueSupplier);

    public function getOrElse(int $index, \Closure $defaultValueSupplier);

    public function getOrNull(int $index);

    public function getOrDefault(int $index, $defaultValue);

    public function findOrNull(\Closure $predicate);

    public function findOrDefault(\Closure $predicate, $defaultValue);

    public function findOrElse(\Closure $predicate, \Closure $defaultValueSupplier);

    public function findIndexedOrNull(\Closure $predicate);

    public function findIndexedOrDefault(\Closure $predicate, $defaultValue);

    public function findIndexedOrElse(\Closure $predicate, \Closure $defaultValueSupplier);

    public function onEach(\Closure $callback): ArrayList;

    public function onEachIndexed(\Closure $callback): ArrayList;

    public function filter(\Closure $predicate): ArrayList;

    public function filterIndexed(\Closure $predicate): ArrayList;

    public function filterNot(\Closure $predicate): ArrayList;

    public function filterNotIndexed(\Closure $predicate): ArrayList;

    public function filterNotNull(): ArrayList;

    public function map(\Closure $transform): ArrayList;

    public function mapIndexed(\Closure $transform): ArrayList;

    public function flatMap(\Closure $transform): ArrayList;

    public function flatMapIndexed(\Closure $transform): ArrayList;

    public function groupBy(\Closure $keySelector): ArrayMap;

    public function groupByIndexed(\Closure $keySelector): ArrayMap;

    public function reduce($initialValue, \Closure $reducer);

    public function reduceIndexed($initialValue, \Closure $reducer);

    public function sortedBy(\Closure $comparator, bool $descending): ArrayList;

    public function sortBy(\Closure $comparator): ArrayList;

    public function sortByDescending(\Closure $comparator): ArrayList;

    public function take(int $amount): ArrayList;

    public function takeWhile(\Closure $predicate): ArrayList;

    public function takeWhileIndexed(\Closure $predicate): ArrayList;

    public function takeLast(int $amount): ArrayList;

    public function drop(int $amount): ArrayList;

    public function dropWhile(\Closure $predicate): ArrayList;

    public function dropWhileIndexed(\Closure $predicate): ArrayList;

    public function chunk(int $batchSize): ArrayList;

    public function nth(int $n): ArrayList;

    public function slice(? $startIndex, ? $stopIndexExclusive, int $step = 1): ArrayList;

    public function sliceByOffsetAndLimit(int $offset, ? $limit, int $step = 1): ArrayList;

## Api (Sequence)

    public static function ofIteratorSupplier(\Closure $supplier): Sequence;

    public function withData(iterable $data): Sequence;

    public function collect(): Sink;

    public function forEachRemaining(\Closure $callback): Void;

    public function reducing($initialValue, \Closure $reducer): Sequence;

    public function reducingIndexed($initialValue, \Closure $reducer): Sequence;

    public function pipe(\Closure $transform): Sequence;

    public function keys(): Sequence;

    public function values(): Sequence;

    public function flip(): Sequence;

    public function reverse(): Sequence;

    public function onEach(\Closure $callback): Sequence;

    public function onEachIndexed(\Closure $callback): Sequence;

    public function map(\Closure $transform): Sequence;

    public function mapIndexed(\Closure $transform): Sequence;

    public function mapKeysByValue(\Closure $keySelector): Sequence;

    public function mapKeysByValueIndexed(\Closure $keySelector): Sequence;

    public function filter(\Closure $predicate): Sequence;

    public function filterIndexed(\Closure $predicate): Sequence;

    public function filterNot(\Closure $predicate): Sequence;

    public function filterNotIndexed(\Closure $predicate): Sequence;

    public function filterNotNull(): Sequence;

    public function flatMap(\Closure $transform): Sequence;

    public function flatMapIndexed(\Closure $transform): Sequence;

    public function groupBy(\Closure $keySelector): Sequence;

    public function groupByIndexed(\Closure $keySelector): Sequence;

    public function take(int $amount): Sequence;

    public function takeWhile(\Closure $predicate): Sequence;

    public function takeWhileIndexed(\Closure $predicate): Sequence;

    public function takeLast(int $amount): Sequence;

    public function drop(int $amount): Sequence;

    public function dropWhile(\Closure $predicate): Sequence;

    public function dropWhileIndexed(\Closure $predicate): Sequence;

    public function sortedBy(bool $descending, \Closure $comparator): Sequence;

    public function sortBy(\Closure $comparator): Sequence;

    public function sortByDescending(\Closure $comparator): Sequence;

    public function chunk(int $batchSize): Sequence;

    public function nth(int $n): Sequence;

    public function slice(? $startIndex, ? $stopIndexExclusive, int $step = 1): Sequence;

    public function sliceByOffsetAndLimit(int $offset, ? $limit, int $step = 1): Sequence;
    
## Api (Flow)

    public static function create(): Flow;

    public static function ofRewindableIteratorSupplier(\Closure $supplier): Flow;

    public function copy(): Flow;

    public function withoutProducer(): Flow;

    public function withProducer(RewindableProducer $producer): Flow;

    public function withProducerOfIterable(iterable $iterable): Flow;

    public function withProducerOfIteratorSupplier(\Closure $iteratorSupplier): Flow;

    public function collect(): Sink;

    public function reducing($initialValue, \Closure $reducer): Flow;

    public function reducingIndexed($initialValue, \Closure $reducer): Flow;

    public function pipe(\Closure $transform): Flow;

    public function keys(): Flow;

    public function values(): Flow;

    public function flip(): Flow;

    public function reverse(): Flow;

    public function onEach(\Closure $callback): Flow;

    public function onEachIndexed(\Closure $callback): Flow;

    public function map(\Closure $transform): Flow;

    public function mapIndexed(\Closure $transform): Flow;

    public function mapKeysByValue(\Closure $keySelector): Flow;

    public function mapKeysByValueIndexed(\Closure $keySelector): Flow;

    public function filter(\Closure $predicate): Flow;

    public function filterIndexed(\Closure $predicate): Flow;

    public function filterNot(\Closure $predicate): Flow;

    public function filterNotIndexed(\Closure $predicate): Flow;

    public function filterNotNull(): Flow;

    public function flatMap(\Closure $transform): Flow;

    public function flatMapIndexed(\Closure $transform): Flow;

    public function groupBy(\Closure $keySelector): Flow;

    public function groupByIndexed(\Closure $keySelector): Flow;

    public function take(int $amount): Flow;

    public function takeWhile(\Closure $predicate): Flow;

    public function takeWhileIndexed(\Closure $predicate): Flow;

    public function takeLast(int $amount): Flow;

    public function drop(int $amount): Flow;

    public function dropWhile(\Closure $predicate): Flow;

    public function dropWhileIndexed(\Closure $predicate): Flow;

    public function sortedBy(bool $descending, \Closure $comparator): Flow;

    public function sortBy(\Closure $comparator): Flow;

    public function sortByDescending(\Closure $comparator): Flow;

    public function chunk(int $batchSize): Flow;

    public function nth(int $n): Flow;

    public function slice(? $startIndex, ? $stopIndexExclusive, int $step = 1): Flow;

    public function sliceByOffsetAndLimit(int $offset, ? $limit, int $step = 1): Flow;
    
