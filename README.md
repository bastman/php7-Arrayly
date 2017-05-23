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
    $ composer require bastman/php7-arrayly 0.0.10

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
        // derive a new flow by applying a different producer
        // note: the old flow will not be affected by this. immutability rockz :)
        
        $derivedFlow = $flow->withProducerOfIteratorSupplier(
                function():\Generator { 
                    yield from self::createCities();
                });
4.      
        // run the drived flow        
        $sink = $derivedFlow
            ->collect()
            ->asArray();
5.
        // and re-run the derived flow - because we can ;)
        $sink = $derivedFlow
            ->collect()
            ->asArray();
             