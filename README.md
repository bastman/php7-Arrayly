# php7-Arrayly
- Arrayly (eager): decorates php array with methods similar to Java Streams / Kotlin Collections
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
    $ composer require bastman/php7-arrayly 0.0.13

## Examples (Arrayly)
- see: tests/examples/arrayly

            Arrayly::ofIterable($cities)
            
            ->map(function ($item) {return $item["country"];})
            ->filter(function ($country) {return $country == 'Germany';})
            ->sortByDescending(function ($a, $b) {return strcasecmp($a, $b);})
            ->drop(1)
            ->take(2)
            
            ->toArray();
-             
            Arrayly::ofIterable($cities)
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

             Sequence::ofIterable($cities)
             
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
              ->pipeTo(function(iterable $iterable){
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
  
## Api (Arrayly)

    public function toArray(): array;

    public function collect(): Sink;

    public function copy(): Arrayly;

    public function withData(array $data): Arrayly;

    public function withKey($key, $value): Arrayly;

    public function keys(bool $strict = true): Arrayly;

    public function values(): Arrayly;

    public function flip(): Arrayly;

    public function shuffle(int $times): Arrayly;

    public function count(): int;

    public function reverse(): Arrayly;

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

    public function onEach(\Closure $callback): Arrayly;

    public function onEachIndexed(\Closure $callback): Arrayly;

    public function filter(\Closure $predicate): Arrayly;

    public function filterIndexed(\Closure $predicate): Arrayly;

    public function filterNot(\Closure $predicate): Arrayly;

    public function filterNotIndexed(\Closure $predicate): Arrayly;

    public function filterNotNull(): Arrayly;

    public function map(\Closure $transform): Arrayly;

    public function mapIndexed(\Closure $transform): Arrayly;

    public function mapKeysByValue(\Closure $keySelector): Arrayly;

    public function mapKeysByValueIndexed(\Closure $keySelector): Arrayly;

    public function flatMap(\Closure $transform): Arrayly;

    public function flatMapIndexed(\Closure $transform): Arrayly;

    public function groupBy(\Closure $keySelector): Arrayly;

    public function groupByIndexed(\Closure $keySelector): Arrayly;

    public function reduce($initialValue, \Closure $reducer);

    public function reduceIndexed($initialValue, \Closure $reducer);

    public function sortedBy(\Closure $comparator, bool $descending): Arrayly;

    public function sortBy(\Closure $comparator): Arrayly;

    public function sortByDescending(\Closure $comparator): Arrayly;

    public function take(int $amount): Arrayly;

    public function takeWhile(\Closure $predicate): Arrayly;

    public function takeWhileIndexed(\Closure $predicate): Arrayly;

    public function drop(int $amount): Arrayly;

    public function dropWhile(\Closure $predicate): Arrayly;

    public function dropWhileIndexed(\Closure $predicate): Arrayly;

    public function chunk(int $batchSize): Arrayly;