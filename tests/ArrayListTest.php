<?php
declare(strict_types=1);

namespace Arrayly\Test;

use function Arrayly\listOf;
use function Arrayly\listOfIterable as ofIterable;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class ArrayListTest extends TestCase
{
    public function testConstruct()
    {
        $arrayList = listOf('a', 'b', 'c');
        $this->assertArrayList($arrayList);
        $this->assertSame(["a", "b", "c"], $arrayList->toArray());

        $arrayList = ofIterable(["a", "b", "c"]);
        $this->assertArrayList($arrayList);
        $this->assertSame(["a", "b", "c"], $arrayList->toArray());

        $arrayList = ofIterable(["foo" => "bar"]);
        $this->assertArrayList($arrayList);
        $this->assertSame(["bar"], $arrayList->toArray());
    }

    private function assertArrayList($actual)
    {
        $this->assertInstanceOf('Arrayly\ArrayList', $actual);
    }
    private function assertArrayMap($actual)
    {
        $this->assertInstanceOf('Arrayly\ArrayMap', $actual);
    }

    private function provideTestCitiesAsList(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }
    private function provideTestCountriesAsMap(): array
    {
        return TestUtils::loadResourceJson('source/countries-map.json');
    }

    public function testMap()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = ofIterable($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->onEach(function ($v) {
            })
            ->onEachIndexed(function ($k, $v) {
            })
            ->toArray();

        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);
    }

    public function testReduce()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = ofIterable($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->reduce('', function ($acc, $v) {
                return $acc.'-'.$v;
            });
        $this->assertSame("-Berlin-Hamburg-London-Manchester-Paris", $sink);
    }

    public function testFilter()
    {
        $source = $this->provideTestCitiesAsList();

        $expected = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
        ];
        $sink = ofIterable($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })->toArray();
        $this->assertSame($expected, $sink);
        $sink = ofIterable($source)
            ->filterIndexed(function ($k, $v) {
                return $v["country"] === "Germany";
            })->toArray();
        $this->assertSame($expected, $sink);

        $source = [
            "a1"=>"a1Value",
            "a2"=>"a2Value",
            "b1"=>"b1Value",
            "b2"=>"b2Value",
            "c1"=>"c1Value",
            "c2"=>"c2Value",
        ];
        $expected = [
            "a1Value",
            "a2Value",

            "c1Value",
            "c2Value",
        ];
        $sink = ofIterable($source)
            ->filterNot(function ($v) {
                return fnmatch('*b*Value*', $v);
            })->toArray();
        $this->assertSame($expected, $sink);
        $sink = ofIterable($source)
            ->filterNotIndexed(function ($k, $v) {
                return fnmatch('*b*Value*', $v);
            })->toArray();
        $this->assertSame($expected, $sink);

        $source = [
            "a1"=>"a1Value",
            "a2"=>null,
            null,
            null,
            "b1"=>"b1Value",
            "b2"=>null,
            null,
            null
        ];
        $expected = [
            "a1Value",
            "b1Value",
        ];
        $sink = ofIterable($source)
            ->filterNotNull()
            ->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testTake()
    {
        $source = ofIterable($this->provideTestCitiesAsList())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = ofIterable($source)
            ->take(0)
            ->toArray();
        $this->assertSame([], $sink);

        $sink = ofIterable($source)
            ->take(1)
            ->toArray();
        $this->assertSame(["Berlin"], $sink);

        $sink = ofIterable($source)
            ->take(3)
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London"], $sink);

        $sink = ofIterable($source)
            ->take(1000)
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);
    }

    public function testDropValues()
    {
        $source = ofIterable($this->provideTestCitiesAsList())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = ofIterable($source)
            ->drop(0)
            ->values()
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);

        $sink = ofIterable($source)
            ->drop(1)
            ->values()
            ->toArray();
        $this->assertSame(["Hamburg", "London", "Manchester", "Paris"], $sink);

        $sink = ofIterable($source)
            ->drop(3)
            ->values()
            ->toArray();
        $this->assertSame(["Manchester", "Paris"], $sink);

        $sink = ofIterable($source)
            ->drop(1000)
            ->values()
            ->toArray();
        $this->assertSame([], $sink);
    }

    public function testDrop()
    {
        $source = [
            "a" => "A",
            "b" => "B",
            "c" => "C",
        ];

        $sink = ofIterable($source)
            ->drop(0)
            ->toArray();
        $this->assertSame(array_values($source), $sink);

        $sink = ofIterable($source)
            ->drop(1)
            ->toArray();
        $this->assertSame([
            "B",
            "C",
        ], $sink);

        $sink = ofIterable($source)
            ->drop(2)
            ->toArray();
        $this->assertSame([
            "C",
        ], $sink);

        $sink = ofIterable($source)
            ->drop(1000)
            ->toArray();
        $this->assertSame(
            [], $sink);

        $source = [
            ["A"],
            ["B"],
            ["C"],
        ];
        $sink = ofIterable($source)
            ->drop(1)
            ->toArray();

        $this->assertSame(
            [
                ["B"],
                ["C"],
            ], $sink);
    }

    public function testTakeWhile()
    {
        $source = [
            "a" => "A",
            "b" => "B",
            "c" => "C",
        ];

        $monitor = new \stdClass();
        $monitor->max = 0;
        $monitor->current = 0;
        $sink = ofIterable($source)
            ->takeWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current <= $monitor->max;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $monitor = new \stdClass();
        $monitor->max = 1;
        $monitor->current = 0;
        $sink = ofIterable($source)
            ->takeWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current <= $monitor->max;
            })
            ->toArray();
        $this->assertSame(["A"], $sink);

        $monitor = new \stdClass();
        $monitor->max = 2;
        $monitor->current = 0;
        $sink = ofIterable($source)
            ->takeWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current <= $monitor->max;
            })
            ->toArray();
        $this->assertSame(["A", "B"], $sink);
    }

    public function testDropWhile()
    {
        $source = [
            "a" => "A",
            "b" => "B",
            "c" => "C",
        ];

        $sink = ofIterable($source)
            ->dropWhile(function ($v) {
                return true;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $sink = ofIterable($source)
            ->dropWhile(function ($v) {
                return false;
            })
            ->toArray();
        $this->assertSame(array_values($source), $sink);

        $monitor = new \stdClass();

        $monitor->current = 0;
        $sink = ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 100;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $monitor->current = 0;
        $sink = ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 4;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $monitor->current = 0;
        $sink = ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 3;
            })
            ->toArray();
        $this->assertSame(["C"], $sink);

        $monitor->current = 0;
        $sink = ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 2;
            })
            ->toArray();
        $this->assertSame(["B", "C"], $sink);

        $monitor->current = 0;
        $sink = ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 1;
            })
            ->toArray();
        $this->assertSame(["A", "B", "C"], $sink);
    }

    public function testGroupBy()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = ofIterable($source)
            ->groupBy(function ($v) {
                return $v["country"];
            });
        $this->assertArrayMap($sink);

        $expected = $this->provideTestCountriesAsMap();

        $this->assertSame($expected, $sink->toArray());
    }

    public function testFlatMap()
    {
        $source = $this->provideTestCountriesAsMap();

        $sink = ofIterable($source)
            ->flatMap(function ($v) {
                return $v;
            })->toArray();

        $expected = $this->provideTestCitiesAsList();

        $this->assertSame($expected, $sink);
    }

    public function testChunk() {
        $source = [
            "a1"=>"a1Value",
            "a2"=>"a2Value",
            "a3"=>"a3Value",
        ];

        $batchSize=1;
        $expected = [
            [
                "a1Value",
            ],
            [
                "a2Value",
            ],
            [
                "a3Value",
            ],
        ];
        $sink = ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=2;
        $expected = [
            [
                "a1Value",
                "a2Value",
            ],
            [
                "a3Value",
            ],
        ];
        $sink = ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=3;
        $expected = [
            [
                "a1Value",
                "a2Value",
                "a3Value"
            ]
        ];
        $sink = ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=99999;
        $expected = [
            [
                "a1Value",
                "a2Value",
                "a3Value"
            ]
        ];
        $sink = ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testTakeLast()
    {
        $source = [
            "a1" => "a1Value",
            "a2" => "a2Value",
            "a3" => "a3Value",
        ];
        $gen=function()use ($source){
            foreach ($source as $k=>$v) {
                yield $k=>$v;
            }
        };

        $limit = 0;
        $expected=[];
        $sink = ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 1;
        $expected=[
            "a3Value",
        ];
        $sink = ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 2;
        $expected=[
            "a2Value",
            "a3Value",
        ];
        $sink = ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 3;
        $expected=array_values($source);
        $sink = ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 10000;
        $expected=array_values($source);
        $sink = ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testNth()
    {
        $source = [
            "a1" => "a1Value",
            "a2" => "a2Value",
            "a3" => "a3Value",
        ];

        $n = 1;
        $expected = array_values($source);
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = 2;
        $expected = [
            "a1Value",
            "a3Value",
        ];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = 3;
        $expected = [
            "a1Value"
        ];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = 4;
        $expected = [ "a1Value"];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -1;
        $expected = [
            "a3Value",
            "a2Value",
            "a1Value",
        ];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -2;
        $expected = [
            "a3Value",
            "a1Value",
        ];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -3;
        $expected = [
            "a3Value"
        ];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -4;
        $expected = [
            "a3Value"
        ];
        $sink = ofIterable($source)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }
}