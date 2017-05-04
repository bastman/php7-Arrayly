# php7-Arrayly
decorates php array with methods similar to Java Streams / Kotlin Collections

inspired by 
- Stringy (OO-Style decorator for strings): https://github.com/danielstjules/Stringy
- Kotlin Collections: https://antonioleiva.com/collection-operations-kotlin/
- Java Stream API: http://winterbe.com/posts/2014/07/31/java8-stream-tutorial-examples/

## Notes
- unlike Java Streams & Kotlin Sequences, operations are not lazy executed
- php generators are not used for the simple approach Arrayly takes.

## Methods
 - filter, map, flatMap, reduce, groupBy, find, sort, ...

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
            
  