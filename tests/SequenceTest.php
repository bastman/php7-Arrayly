<?php
namespace Arrayly\Test;

use Arrayly\Arrayly;
use Arrayly\Producers\RewindableProducer;
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

        $sink = S::ofIterable($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->onEach(function ($v) {
            })
            ->onEachIndexed(function ($k, $v) {
            })
            ->collect()->toArray();

        $this->assertSame($expected, $sink);

        $sink = S::ofIterable($source)
            ->mapIndexed(function ($k, $v) {
                return $v["city"];
            })
            ->onEach(function ($v) {
            })
            ->onEachIndexed(function ($k, $v) {
            })
            ->collect()->toArray();

        $this->assertSame($expected, $sink);
    }

    public function testFlatMap()
    {
        $source = $this->provideTestCountriesAsMap();
        $expected = $this->provideTestCitiesAsList();

        $sink = S::ofIterable($source)
            ->flatMap(function ($v) {
                return $v;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $sink = S::ofIterable($source)
            ->flatMapIndexed(function ($k, $v) {
                return $v;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

    }


    public function testFilter()
    {
        $source = $this->provideTestCitiesAsList();

        $expected = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
        ];
        $sink = S::ofIterable($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->filterIndexed(function ($k, $v) {
                return $v["country"] === "Germany";
            })
            ->collect()->toArray();
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
            "a1"=>"a1Value",
            "a2"=>"a2Value",

            "c1"=>"c1Value",
            "c2"=>"c2Value",
        ];
        $sink = S::ofIterable($source)
            ->filterNot(function ($v) {
                return fnmatch('*b*Value*', $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->filterNotIndexed(function ($k, $v) {
                return fnmatch('*b*Value*', $v);
            })
            ->collect()->toArray();
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
            "a1"=>"a1Value",
            "b1"=>"b1Value",
        ];
        $sink = S::ofIterable($source)
            ->filterNotNull()
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testGroupBy()
    {
        $source = $this->provideTestCitiesAsList();
        $expected = $this->provideTestCountriesAsMap();

        $sink = S::ofIterable($source)
            ->groupBy(function ($v) {
                return $v["country"];
            })
            ->collect()->toArray();

        $this->assertSame($expected, $sink);

        $sink = S::ofIterable($source)
            ->groupByIndexed(function ($k, $v) {
                return $v["country"];
            })
            ->collect()->toArray();

        $this->assertSame($expected, $sink);
    }

    public function testSortByAsc()
    {
        $source = $this->provideTestCitiesAsList();

        // asc
        $expected = $this->provideTestCitiesAsListAscending();
        $sink = S::ofIterable($source)
            ->sortBy(function ($v1, $v2) {
                return strcasecmp($v1["city"], $v2["city"]);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testSortByDesc()
    {
        $source = $this->provideTestCitiesAsList();

        // desc
        $expected = $this->provideTestCitiesAsListDescending();
        $sink = S::ofIterable($source)
            ->sortByDescending(function ($v1, $v2) {
                return strcasecmp($v1["city"], $v2["city"]);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testTake()
    {
        $source = S::ofIterable($this->provideTestCitiesAsList())
            ->map(function ($v) {
                return $v["city"];
            })
            ->collect()->toArray();

        $sink = S::ofIterable($source)
            ->take(0)
            ->collect()->toArray();
        $this->assertSame([], $sink);

        $sink = S::ofIterable($source)
            ->take(1)
            ->collect()->toArray();
        $this->assertSame(["Berlin"], $sink);

        $sink = S::ofIterable($source)
            ->take(3)
            ->collect()->toArray();
        $this->assertSame(["Berlin", "Hamburg", "London"], $sink);

        $sink = S::ofIterable($source)
            ->take(1000)
            ->collect()->toArray();
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
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) {
                return true;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) {
                return true;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $expected = [];
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) {
                return false;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) {
                return false;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);


        $pattern = "*D*";
        $expected = [];
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*A*";
        $expected = [
            "a1" => "A1",
            "a2" => "A2",
        ];
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*C*";
        $expected = [];
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*1*";
        $expected = [
            "a1" => "A1",
        ];
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*2*";
        $expected = [];
        $sink = S::ofIterable($source)
            ->takeWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testDrop()
    {
        $source = [
            "a" => "A",
            "b" => "B",
            "c" => "C",
        ];

        $sink = S::ofIterable($source)
            ->drop(0)
            ->collect()->toArray();
        $this->assertSame($source, $sink);

        $sink = S::ofIterable($source)
            ->drop(1)
            ->collect()->toArray();
        $this->assertSame([
            "b" => "B",
            "c" => "C",
        ], $sink);

        $sink = S::ofIterable($source)
            ->drop(2)
            ->collect()->toArray();
        $this->assertSame([
            "c" => "C",
        ], $sink);

        $sink = S::ofIterable($source)
            ->drop(1000)
            ->collect()->toArray();
        $this->assertSame(
            [], $sink);

        $source = [
            ["A"],
            ["B"],
            ["C"],
        ];
        $sink = S::ofIterable($source)
            ->drop(1)
            ->collect()->toArray();

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
        $sink = S::ofIterable($source)
            ->dropWhile(function ($v) {
                return true;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->dropWhileIndexed(function ($k, $v) {
                return true;
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);


        $pattern = "*D*";
        $expected = $source;
        $sink = S::ofIterable($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);


        $pattern = "*A*";
        $expected = [
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];
        $sink = S::ofIterable($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*C*";
        $expected = $source;
        $sink = S::ofIterable($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*1*";
        $expected = [
            "a2" => "A2",
            "b1" => "B1",
            "b2" => "B2",
            "c1" => "C1",
            "c2" => "C2",
        ];
        $sink = S::ofIterable($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $pattern = "*2*";
        $expected = $source;
        $sink = S::ofIterable($source)
            ->dropWhile(function ($v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                return fnmatch($pattern, $v);
            })
            ->collect()->toArray();
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
        $sink = S::ofIterable($source)
            ->reducing([], function ($acc, $v) { return $v;})
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->reducingIndexed([], function ($acc, $k, $v) { return $v;})
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $expected=['A1A2B1B2C1C2'];
        $sink = S::ofIterable($source)
            ->reducing("", function (string $acc, string $v) { return $acc.$v;})
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
        $sink = S::ofIterable($source)
            ->reducingIndexed("", function (string $acc, $k, string $v) { return $acc.$v;})
            ->collect()->toArray();
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

        $sink = S::ofIterable($source)
            ->mapKeysByValueIndexed(function ($k, $v) {return strtoupper($k);})
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $expected = [
            "a1:a1Value"=>"a1Value",
            "b1:b1Value"=>"b1Value",
        ];
        $sink = S::ofIterable($source)
            ->mapKeysByValueIndexed(function ($k, $v) {return $k.':'.$v;})
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $expected = [
            "A1VALUE"=>"a1Value",
            "B1VALUE"=>"b1Value",
        ];
        $sink = S::ofIterable($source)
            ->mapKeysByValue(function ($v) {return strtoupper($v);})
            ->collect()->toArray();
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
                "a1"=>"a1Value",
            ],
            [
                "a2"=>"a2Value",
            ],
            [
                "a3"=>"a3Value",
            ],
        ];
        $sink = S::ofIterable($source)
            ->chunk($batchSize)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=2;
        $expected = [
            [
                "a1"=>"a1Value",
                "a2"=>"a2Value",
            ],
            [
                "a3"=>"a3Value",
            ],
        ];
        $sink = S::ofIterable($source)
            ->chunk($batchSize)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=3;
        $expected = [
            [
                "a1"=>"a1Value",
                "a2"=>"a2Value",
                "a3"=>"a3Value"
            ]
        ];
        $sink = S::ofIterable($source)
            ->chunk($batchSize)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=99999;
        $expected = [
            [
                "a1"=>"a1Value",
                "a2"=>"a2Value",
                "a3"=>"a3Value"
            ]
        ];
        $sink = S::ofIterable($source)
            ->chunk($batchSize)
            ->collect()->toArray();
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
        $sink = S::ofIterable(RewindableProducer::ofIteratorSupplier($gen))
            ->takeLast($limit)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $limit = 1;
        $expected=[
            "a3" => "a3Value",
        ];
        $sink = S::ofIterable(RewindableProducer::ofIteratorSupplier($gen))
            ->takeLast($limit)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $limit = 2;
        $expected=[
            "a2" => "a2Value",
            "a3" => "a3Value",
        ];
        $sink = S::ofIterable(RewindableProducer::ofIteratorSupplier($gen))
            ->takeLast($limit)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $limit = 3;
        $expected=$source;
        $sink = S::ofIterable(RewindableProducer::ofIteratorSupplier($gen))
            ->takeLast($limit)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $limit = 10000;
        $expected=$source;
        $sink = S::ofIterable(RewindableProducer::ofIteratorSupplier($gen))
            ->takeLast($limit)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }

    public function testNth()
    {
        $source = [
            "a1" => "a1Value",
            "a2" => "a2Value",
            "a3" => "a3Value",
        ];
        $gen=function() use ($source){
            yield from $source;
        };

        $n = 1;
        $expected = $source;
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = 2;
        $expected = [
            "a1" => "a1Value",
            "a3" => "a3Value",
        ];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = 3;
        $expected = [
            "a1" => "a1Value"
        ];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = 4;
        $expected = [ "a1" => "a1Value"];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -1;
        $expected = [
            "a3" => "a3Value",
            "a2" => "a2Value",
            "a1" => "a1Value",
        ];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -2;
        $expected = [
            "a3" => "a3Value",
            "a1" => "a1Value",
        ];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -3;
        $expected = [
            "a3" => "a3Value"
        ];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);

        $n = -4;
        $expected = [
            "a3" => "a3Value"
        ];
        $sink = S::ofIteratorSupplier($gen)
            ->nth($n)
            ->collect()->toArray();
        $this->assertSame($expected, $sink);
    }


    public function testSliceSubset()
    {
        $source = [
            "a1" => "a1Value",
            "a2" => "a2Value",
            "a3" => "a3Value",
            "a4" => "a4Value",
            "a5" => "a5Value",
        ];

        $tests=[
            // step
            [
                'given'=>['step'=>1, 'start'=>null, 'stop'=>null],
                'expected' => $source
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>4, 'start'=>null, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>5, 'start'=>null, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value"
                ]
            ],
            [
                'given'=>['step'=>100, 'start'=>null, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value"
                ]
            ],
            // start
            [
                'given'=>['step'=>1, 'start'=>2, 'stop'=>null],
                'expected' => [
                    "a3" => "a3Value",
                    "a4" => "a4Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>2, 'stop'=>null],
                'expected' => [
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>4, 'stop'=>null],
                'expected' => [
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>5, 'stop'=>null],
                'expected' => []
            ],
            // start: negative
            [
                'given'=>['step'=>2, 'start'=>-10, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>-5, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>-4, 'stop'=>null],
                'expected' => [
                    "a2" => "a2Value",
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>-3, 'stop'=>null],
                'expected' => [
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>-2, 'stop'=>null],
                'expected' => [
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>-1, 'stop'=>null],
                'expected' => [
                    "a5" => "a5Value",
                ]
            ],
            // stop
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>0],
                'expected' => []
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>1],
                'expected' => [
                    "a1" => "a1Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>2],
                'expected' => [
                    "a1" => "a1Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>3],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>4],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>5],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>1000],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            // stop negative
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>-1],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value"
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>-2],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value"
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>-3],
                'expected' => [
                    "a1" => "a1Value"
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>-4],
                'expected' => [
                    "a1" => "a1Value"
                ]
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>-5],
                'expected' => []
            ],
            [
                'given'=>['step'=>2, 'start'=>null, 'stop'=>-1000],
                'expected' => []
            ],

            // mixed (start, stop, step)
            [
                'given'=>['start'=>3, 'stop'=>-1, 'step'=>2],
                'expected' => [
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['start'=>1, 'stop'=>-1, 'step'=>1],
                'expected' => [
                    "a2" => "a2Value",
                    "a3" => "a3Value",
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['start'=>1, 'stop'=>-2, 'step'=>1],
                'expected' => [
                    "a2" => "a2Value",
                    "a3" => "a3Value"
                ]
            ],
            [
                'given'=>['start'=>-4, 'stop'=>-1, 'step'=>1],
                'expected' => [
                    "a2" => "a2Value",
                    "a3" => "a3Value",
                    "a4" => "a4Value",
                ]
            ],

        ];

        Arrayly::ofIterable($tests)->onEachIndexed(function ($testCaseIndex, array $testCase) use($source){
            $start=$testCase['given']['start'];
            $stop=$testCase['given']['stop'];
            $step=$testCase['given']['step'];

            $sink = S::ofIterable($source)
                ->slice($start, $stop, $step)
                ->collect()->toArray();
            try{
                $this->assertSame($testCase['expected'], $sink);
            }catch (\Throwable $all) {
                echo " --> Testcase at index: ".$testCaseIndex. " failed! testCase=".json_encode($testCase);

                throw $all;
            }

        });

    }
}