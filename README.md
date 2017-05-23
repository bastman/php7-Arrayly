# php7-Arrayly
- Arrayly (eager): decorates php array with methods similar to Java Streams / Kotlin Collections
- Sequence (lazy, consume-once): provides fluid interface to php generators
- Flow (lazy, consume-rewindable, FBP): kind of flow-based-programming-style (FBP) for replayable transformations


inspired by 
- nikic/iter: https://github.com/nikic/iter
- Kotlin Collections: https://antonioleiva.com/collection-operations-kotlin/
- Java Stream API: http://winterbe.com/posts/2014/07/31/java8-stream-tutorial-examples/
- Stringy (OO-Style decorator for strings): https://github.com/danielstjules/Stringy

## Alternative Concepts
- Transducers: https://github.com/mtdowling/transducers.php

## Notes
- Experimental.

## Methods
 - filter, map, flatMap, reduce, groupBy, find, sort, ...
 
## Install
    $ composer require bastman/php7-arrayly 0.0.9

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
            ->asArray();
        
3.        
        // run the same flow again, but with a different producer (supplier)
        $citiesSupplier = function () {
            yield from self::createCities();
        };
4.
        // derive a new flow by applying a different producer
        $derivedflowWithOtherProducer = $flow->withProducerOfIteratorSupplier($citiesSupplier);
        $sink = $flowWithOtherProducer
            ->collect()
            ->asArray();
5.
        // and re-run it - because we can ;)
        $sink = $derivedflowWithOtherProducer
            ->collect()
            ->asArray();
             