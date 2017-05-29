<?php
declare(strict_types=1);

namespace Arrayly\Test;

use Arrayly\ArrayMap as A;

use function Arrayly\mapOfIterable;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class ArrayMapTest extends TestCase
{
    public function testConstruct()
    {
        $arrayMap = A::ofIterable(["foo" => "bar"]);
        $this->assertArrayMap($arrayMap);

        $this->assertEquals('bar', (string)$arrayMap->getOrNull('foo'));
        $this->assertSame(["foo" => "bar"], $arrayMap->toArray());

        $arrayMap = mapOfIterable(["foo" => "bar"]);
        $this->assertArrayMap($arrayMap);
        $this->assertSame(["foo" => "bar"], $arrayMap->toArray());
    }

    private function assertArrayMap($actual)
    {
        $this->assertInstanceOf('Arrayly\ArrayMap', $actual);
    }

    public function testMap()
    {
        $source = $this->provideTestCitiesAsList();

        $sink = mapOfIterable($source)
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

        $expected = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
        ];
        $sink = A::ofIterable($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })->toArray();
        $this->assertSame($expected, $sink);
        $sink = A::ofIterable($source)
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
            "a1"=>"a1Value",
            "a2"=>"a2Value",

            "c1"=>"c1Value",
            "c2"=>"c2Value",
        ];
        $sink = A::ofIterable($source)
            ->filterNot(function ($v) {
                return fnmatch('*b*Value*', $v);
            })->toArray();
        $this->assertSame($expected, $sink);
        $sink = A::ofIterable($source)
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
            "a1"=>"a1Value",
            "b1"=>"b1Value",
        ];
        $sink = A::ofIterable($source)
            ->filterNotNull()
            ->toArray();
        $this->assertSame($expected, $sink);
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
        $sink = A::ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
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
        $sink = A::ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=3;
        $expected = [
            [
                "a1"=>"a1Value",
                "a2"=>"a2Value",
                "a3"=>"a3Value"
            ]
        ];
        $sink = A::ofIterable($source)
            ->chunk($batchSize)
            ->toArray();
        $this->assertSame($expected, $sink);

        $batchSize=99999;
        $expected = [
            [
                "a1"=>"a1Value",
                "a2"=>"a2Value",
                "a3"=>"a3Value"
            ]
        ];
        $sink = A::ofIterable($source)
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
        $sink = A::ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 1;
        $expected=[
            "a3" => "a3Value",
        ];
        $sink = A::ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 2;
        $expected=[
            "a2" => "a2Value",
            "a3" => "a3Value",
        ];
        $sink = A::ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 3;
        $expected=$source;
        $sink = A::ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);

        $limit = 10000;
        $expected=$source;
        $sink = A::ofIterable($gen())
            ->takeLast($limit)
            ->toArray();
        $this->assertSame($expected, $sink);
    }



    public function testSlice()
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

        A::ofIterable($tests)->onEachIndexed(function ($testCaseIndex, array $testCase) use($source){
            $start=$testCase['given']['start'];
            $stop=$testCase['given']['stop'];
            $step=$testCase['given']['step'];

            $sink = A::ofIterable($source)
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


    public function testSliceByOffsetAndLimit()
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
                'given'=>['step'=>1, 'offset'=>0, 'limit'=>null],
                'expected' => $source
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>4, 'offset'=>0, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>5, 'offset'=>0, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value"
                ]
            ],
            [
                'given'=>['step'=>100, 'offset'=>0, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value"
                ]
            ],
            // offset
            [
                'given'=>['step'=>1, 'offset'=>2, 'limit'=>null],
                'expected' => [
                    "a3" => "a3Value",
                    "a4" => "a4Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>2, 'limit'=>null],
                'expected' => [
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>4, 'limit'=>null],
                'expected' => [
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>5, 'limit'=>null],
                'expected' => []
            ],
            // offset: negative
            [
                'given'=>['step'=>2, 'offset'=>-10, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>-5, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>-4, 'limit'=>null],
                'expected' => [
                    "a2" => "a2Value",
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>-3, 'limit'=>null],
                'expected' => [
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>-2, 'limit'=>null],
                'expected' => [
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>-1, 'limit'=>null],
                'expected' => [
                    "a5" => "a5Value",
                ]
            ],
            // limit
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>null],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>0],
                'expected' => []
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>1],
                'expected' => [
                    "a1" => "a1Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>2],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>3],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>4],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>5],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['step'=>2, 'offset'=>0, 'limit'=>1000],
                'expected' => [
                    "a1" => "a1Value",
                    "a3" => "a3Value",
                    "a5" => "a5Value",
                ]
            ],

            // mixed (offset, limit, step)
            [
                'given'=>['offset'=>3, 'limit'=>null, 'step'=>2],
                'expected' => [
                    "a4" => "a4Value",
                ]
            ],
            [
                'given'=>['offset'=>1, 'limit'=>1000, 'step'=>1],
                'expected' => [
                    "a2" => "a2Value",
                    "a3" => "a3Value",
                    "a4" => "a4Value",
                    "a5" => "a5Value",
                ]
            ],
            [
                'given'=>['offset'=>-4, 'limit'=>2, 'step'=>3],
                'expected' => [
                    "a2" => "a2Value",
                    "a5" => "a5Value"
                ]
            ],
            [
                'given'=>['offset'=>-4, 'limit'=>4, 'step'=>2],
                'expected' => [
                    "a2" => "a2Value",
                    "a4" => "a4Value",
                ]
            ],
        ];

        A::ofIterable($tests)->onEachIndexed(function ($testCaseIndex, array $testCase) use($source){

            try{
                $offset=$testCase['given']['offset'];
                $limit=$testCase['given']['limit'];
                $step=$testCase['given']['step'];
                $sink = A::ofIterable($source)
                    ->sliceByOffsetAndLimit($offset, $limit, $step)
                    ->collect()->toArray();
                $this->assertSame($testCase['expected'], $sink);
            }catch (\Throwable $all) {
                echo " --> Testcase at index: ".$testCaseIndex. " failed! testCase=".json_encode($testCase);

                throw $all;
            }

        });

    }

}