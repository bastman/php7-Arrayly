<?php
namespace Arrayly\Test;

use Arrayly\Sequence as S;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class SequenceTest extends TestCase
{
    private function provideTestCitiesAsList(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }

    private function provideTestCitiesAsListAscending(): array
    {
        return TestUtils::loadResourceJson('source/cities-list-asc.json');
    }

    private function provideTestCitiesAsListDescending(): array
    {
        return TestUtils::loadResourceJson('source/cities-list-desc.json');
    }

    private function provideTestCountriesAsMap(): array
    {
        return TestUtils::loadResourceJson('source/countries-map.json');
    }

    public function testMap()
    {
        $source = $this->provideTestCitiesAsList();
        $expected = ["Berlin", "Hamburg", "London", "Manchester", "Paris"];

        $sink = S::ofArray($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->onEach(function ($v) {
            })
            ->onEachIndexed(function ($k, $v) {
            })
            ->toArray();

        $this->assertSame($expected, $sink);

        $sink = S::ofArray($source)
            ->mapIndexed(function ($k, $v) {
                return $v["city"];
            })
            ->onEach(function ($v) {
            })
            ->onEachIndexed(function ($k, $v) {
            })
            ->toArray();

        $this->assertSame($expected, $sink);
    }

    public function testFlatMap()
    {
        $source = $this->provideTestCountriesAsMap();
        $expected = $this->provideTestCitiesAsList();

        $sink = S::ofArray($source)
            ->flatMap(function ($v) {
                return $v;
            })->toArray();
        $this->assertSame($expected, $sink);

        $sink = S::ofArray($source)
            ->flatMapIndexed(function ($k, $v) {
                return $v;
            })->toArray();
        $this->assertSame($expected, $sink);

    }

    /*

    public function testReduce()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = A::ofArray($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->reduce('', function ($acc, $v) {
                return $acc.'-'.$v;
            });
        $this->assertSame("-Berlin-Hamburg-London-Manchester-Paris", $sink);
    }
     */

    public function testFilter()
    {
        $source = $this->provideTestCitiesAsList();
        $expected = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
        ];

        $sink = S::ofArray($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })->toArray();
        $this->assertSame($expected, $sink);

        $sink = S::ofArray($source)
            ->filterIndexed(function ($k, $v) {
                return $v["country"] === "Germany";
            })->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testGroupBy()
    {
        $source = $this->provideTestCitiesAsList();
        $expected = $this->provideTestCountriesAsMap();

        $sink = S::ofArray($source)
            ->groupBy(function ($v) {
                return $v["country"];
            })->toArray();

        $this->assertSame($expected, $sink);

        $sink = S::ofArray($source)
            ->groupByIndexed(function ($k, $v) {
                return $v["country"];
            })->toArray();

        $this->assertSame($expected, $sink);
    }

    public function testSortByAsc()
    {
        $source = $this->provideTestCitiesAsList();

        // asc
        $expected = $this->provideTestCitiesAsListAscending();
        $sink = S::ofArray($source)
            ->sortBy(function ($v1, $v2) {
                return strcasecmp($v1["city"], $v2["city"]);
            }, false)
            ->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testSortByDesc()
    {
        $source = $this->provideTestCitiesAsList();

        // desc
        $expected = $this->provideTestCitiesAsListDescending();
        $sink = S::ofArray($source)
            ->sortBy(function ($v1, $v2) {
                return strcasecmp($v1["city"], $v2["city"]);
            }, true)
            ->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testTake()
    {
        $source = S::ofArray($this->provideTestCitiesAsList())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = S::ofArray($source)
            ->take(0)
            ->toArray();
        $this->assertSame([], $sink);

        $sink = S::ofArray($source)
            ->take(1)
            ->toArray();
        $this->assertSame(["Berlin"], $sink);

        $sink = S::ofArray($source)
            ->take(3)
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London"], $sink);

        $sink = S::ofArray($source)
            ->take(1000)
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);
    }

    public function testTakeWhile()
    {
        $source = [
            "a1" => "A1",
            "a2" => "A2",
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];

        $expected = $source;
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) {
                return true;
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) {
                return true;
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $expected = [];
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) {
                return false;
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) {
                return false;
            })
            ->toArray();
        $this->assertSame($expected, $sink);


        $pattern = "*D*";
        $expected = [];
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*A*";
        $expected = [
            "a1" => "A1",
            "a2" => "A2",
        ];
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*C*";
        $expected = [];
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*1*";
        $expected = [
            "a1" => "A1",
        ];
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*2*";
        $expected = [];
        $sink = S::ofArray($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testDrop()
    {
        $source = [
            "a" => "A",
            "b" => "B",
            "c" => "C",
        ];

        $sink = S::ofArray($source)
            ->drop(0)
            ->toArray();
        $this->assertSame($source, $sink);

        $sink = S::ofArray($source)
            ->drop(1)
            ->toArray();
        $this->assertSame([
            "b" => "B",
            "c" => "C",
        ], $sink);

        $sink = S::ofArray($source)
            ->drop(2)
            ->toArray();
        $this->assertSame([
            "c" => "C",
        ], $sink);

        $sink = S::ofArray($source)
            ->drop(1000)
            ->toArray();
        $this->assertSame(
            [], $sink);

        $source = [
            ["A"],
            ["B"],
            ["C"],
        ];
        $sink = S::ofArray($source)
            ->drop(1)
            ->toArray();

        $this->assertSame(
            [
                "1" => ["B"],
                "2" => ["C"],
            ], $sink);
    }

    public function testDropWhile()
    {
        $source = [
            "a1" => "A1",
            "a2" => "A2",
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];

        $expected = [];
        $sink = S::ofArray($source)
            ->dropWhile(function ($v) {
                return true;
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->dropWhileIndexed(function ($k, $v) {
                return true;
            })
            ->toArray();
        $this->assertSame($expected, $sink);


        $pattern = "*D*";
        $expected = $source;
        $sink = S::ofArray($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);


        $pattern = "*A*";
        $expected = [
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];
        $sink = S::ofArray($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*C*";
        $expected = $source;
        $sink = S::ofArray($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*1*";
        $expected = [
            "a2" => "A2",
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];
        $sink = S::ofArray($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*2*";
        $expected = $source;
        $sink = S::ofArray($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->toArray();
        $this->assertSame($expected, $sink);

    }

    public function testReducing()
    {
        $source = [
            "a1" => "A1",
            "a2" => "A2",
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];

        $expected=["C2"];
        $sink = S::ofArray($source)
            ->reducing([], function ($acc, $v) { return $v;})
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->reducingIndexed([], function ($acc, $k, $v) { return $v;})
            ->toArray();
        $this->assertSame($expected, $sink);

        $expected=['A1A2B1B2C1C2'];
        $sink = S::ofArray($source)
            ->reducing("", function (string $acc, string $v) { return $acc.$v;})
            ->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofArray($source)
            ->reducingIndexed("", function (string $acc, $k, string $v) { return $acc.$v;})
            ->toArray();
        $this->assertSame($expected, $sink);
    }

}