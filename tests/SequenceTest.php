<?php
/**
 * Created by PhpStorm.
 * User: sebastians
 * Date: 17.05.17
 * Time: 15:06
 */

namespace Arrayly\Test;

use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;
use Arrayly\Sequence\Sequence as S;
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
            ->onEach(function ($v) {})
            ->onEachIndexed(function ($k, $v) {})
            ->toArray();

        $this->assertSame($expected, $sink);

        $sink = S::ofArray($source)
            ->mapIndexed(function ($k, $v) {
                return $v["city"];
            })
            ->onEach(function ($v) {})
            ->onEachIndexed(function ($k, $v) {})
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
                return strcasecmp($v1["city"],$v2["city"]);
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
                return strcasecmp($v1["city"],$v2["city"]);
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

}