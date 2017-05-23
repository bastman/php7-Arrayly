<?php
declare(strict_types=1);

namespace Arrayly\Test;

use Arrayly\Arrayly as A;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class ArraylyTestCase extends TestCase
{
    public function testConstruct()
    {
        $arrayly = A::ofIterable(["foo" => "bar"]);
        $this->assertArrayly($arrayly);
        $this->assertEquals('bar', (string)$arrayly->getOrNull('foo'));
    }

    public function assertArrayly($actual)
    {
        $this->assertInstanceOf('Arrayly\Arrayly', $actual);
    }

    public function testMap()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = A::ofIterable($source)
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

    private function provideTestCitiesAsList(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }

    public function testReduce()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = A::ofIterable($source)
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

        $sink = A::ofIterable($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })->toArray();

        $this->assertSame([
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
        ], $sink);
    }

    public function testTake()
    {
        $source = A::ofIterable($this->provideTestCitiesAsList())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = A::ofIterable($source)
            ->take(0)
            ->toArray();
        $this->assertSame([], $sink);

        $sink = A::ofIterable($source)
            ->take(1)
            ->toArray();
        $this->assertSame(["Berlin"], $sink);

        $sink = A::ofIterable($source)
            ->take(3)
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London"], $sink);

        $sink = A::ofIterable($source)
            ->take(1000)
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);
    }

    public function testDropValues()
    {
        $source = A::ofIterable($this->provideTestCitiesAsList())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = A::ofIterable($source)
            ->drop(0)
            ->values()
            ->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);

        $sink = A::ofIterable($source)
            ->drop(1)
            ->values()
            ->toArray();
        $this->assertSame(["Hamburg", "London", "Manchester", "Paris"], $sink);

        $sink = A::ofIterable($source)
            ->drop(3)
            ->values()
            ->toArray();
        $this->assertSame(["Manchester", "Paris"], $sink);

        $sink = A::ofIterable($source)
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

        $sink = A::ofIterable($source)
            ->drop(0)
            ->toArray();
        $this->assertSame($source, $sink);

        $sink = A::ofIterable($source)
            ->drop(1)
            ->toArray();
        $this->assertSame([
            "b" => "B",
            "c" => "C",
        ], $sink);

        $sink = A::ofIterable($source)
            ->drop(2)
            ->toArray();
        $this->assertSame([
            "c" => "C",
        ], $sink);

        $sink = A::ofIterable($source)
            ->drop(1000)
            ->toArray();
        $this->assertSame(
            [], $sink);

        $source = [
            ["A"],
            ["B"],
            ["C"],
        ];
        $sink = A::ofIterable($source)
            ->drop(1)
            ->toArray();

        $this->assertSame(
            [
                "1" => ["B"],
                "2" => ["C"],
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
        $sink = A::ofIterable($source)
            ->takeWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current <= $monitor->max;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $monitor = new \stdClass();
        $monitor->max = 1;
        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->takeWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current <= $monitor->max;
            })
            ->toArray();
        $this->assertSame(["a" => "A"], $sink);

        $monitor = new \stdClass();
        $monitor->max = 2;
        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->takeWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current <= $monitor->max;
            })
            ->toArray();
        $this->assertSame(["a" => "A", "b" => "B"], $sink);
    }

    public function testDropWhile()
    {
        $source = [
            "a" => "A",
            "b" => "B",
            "c" => "C",
        ];

        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) {
                return true;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) {
                return false;
            })
            ->toArray();
        $this->assertSame($source, $sink);

        $monitor = new \stdClass();

        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 100;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 4;
            })
            ->toArray();
        $this->assertSame([], $sink);

        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 3;
            })
            ->toArray();
        $this->assertSame(["c" => "C"], $sink);

        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 2;
            })
            ->toArray();
        $this->assertSame(["b" => "B", "c" => "C"], $sink);

        $monitor->current = 0;
        $sink = A::ofIterable($source)
            ->dropWhile(function ($v) use ($monitor) {
                $monitor->current++;

                return $monitor->current < 1;
            })
            ->toArray();
        $this->assertSame(["a" => "A", "b" => "B", "c" => "C"], $sink);
    }

    public function testGroupBy()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = A::ofIterable($source)
            ->groupBy(function ($v) {
                return $v["country"];
            })->toArray();

        $expected = $this->provideTestCountriesAsMap();

        $this->assertSame($expected, $sink);
    }

    private function provideTestCountriesAsMap(): array
    {
        return TestUtils::loadResourceJson('source/countries-map.json');
    }

    public function testFlatMap()
    {
        $source = $this->provideTestCountriesAsMap();

        $sink = A::ofIterable($source)
            ->flatMap(function ($v) {
                return $v;
            })->toArray();

        $expected = $this->provideTestCitiesAsList();

        $this->assertSame($expected, $sink);
    }

    public function testMapKeys() {
        $source = [
            "a1"=>"a1Value",
            "b1"=>"b1Value",
        ];
        $expected = [
            "A1"=>"a1Value",
            "B1"=>"b1Value",
        ];

        $sink = A::ofIterable($source)
            ->mapKeysByValueIndexed(function ($k, $v) {return strtoupper($k);})
            ->toArray();
        $this->assertSame($expected, $sink);

        $expected = [
            "a1:a1Value"=>"a1Value",
            "b1:b1Value"=>"b1Value",
        ];
        $sink = A::ofIterable($source)
            ->mapKeysByValueIndexed(function ($k, $v) {return $k.':'.$v;})
            ->toArray();
        $this->assertSame($expected, $sink);

        $expected = [
            "A1VALUE"=>"a1Value",
            "B1VALUE"=>"b1Value",
        ];
        $sink = A::ofIterable($source)
            ->mapKeysByValue(function ($v) {return strtoupper($v);})
            ->toArray();
        $this->assertSame($expected, $sink);
    }
}