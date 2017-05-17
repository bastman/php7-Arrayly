# php7-Arrayly
- Arrayly (eager): decorates php array with methods similar to Java Streams / Kotlin Collections
- Sequence (lazy): provides fluid interface to generators
- Pipeline (lazy): kind of flow-style (FBP) for replayable transformations


inspired by 
- Stringy (OO-Style decorator for strings): https://github.com/danielstjules/Stringy
- nikic/iter: https://github.com/nikic/iter
- Kotlin Collections: https://antonioleiva.com/collection-operations-kotlin/
- Java Stream API: http://winterbe.com/posts/2014/07/31/java8-stream-tutorial-examples/

## Alternative Concepts
- Transducers: https://github.com/mtdowling/transducers.php

## Notes
- Experimental.

## Methods
 - filter, map, flatMap, reduce, groupBy, find, sort, ...
 
## Install
    $ composer require bastman/php7-arrayly 0.0.1

## Examples
- see: tests/examples

            Arrayly::ofArray($cities)
            
            ->map(function ($item) {return $item["country"];})
            ->filter(function ($country) {return $country == 'Germany';})
            ->sortBy(function ($a, $b) {return strcasecmp($a, $b);}, true)
            ->reverse()
            ->drop(1)
            ->take(2)
            
            ->toArray();
            
  