<?php
/**
 * Created by PhpStorm.
 * User: sebastians
 * Date: 17.05.17
 * Time: 15:06
 */

namespace Arrayly\Test;

use Arrayly\Flow;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class FlowTest extends TestCase
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

        $flow = Flow::ofIterable($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->onEach(function ($v) {})
            ->onEachIndexed(function ($k, $v) {})
        ;

        $this->assertSame($expected, $flow->collect()->asArray());

        $flow = Flow::ofIterable($source)
            ->mapIndexed(function ($k, $v) {
                return $v["city"];
            })
            ->onEach(function ($v) {})
            ->onEachIndexed(function ($k, $v) {})
        ;

        $this->assertSame($expected, $flow->collect()->asArray());
    }

    public function testFlatMap()
    {
        $source = $this->provideTestCountriesAsMap();
        $expected = $this->provideTestCitiesAsList();

        $flow = Flow::ofIterable($source)
            ->flatMap(function ($v) {
                return $v;
            });
        $this->assertSame($expected, $flow->collect()->asArray());

        $flow = Flow::ofIterable($source)
            ->flatMapIndexed(function ($k, $v) {
                return $v;
            });
        $this->assertSame($expected, $flow->collect()->asArray());
    }


    public function testFilter()
    {
        $source = $this->provideTestCitiesAsList();
        $expected = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
        ];

        $sink = Flow::ofIterable($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })->collect()->asArray();
        $this->assertSame($expected, $sink);

        $sink = Flow::ofIterable($source)
            ->filterIndexed(function ($k, $v) {
                return $v["country"] === "Germany";
            })->collect()->asArray();
        $this->assertSame($expected, $sink);
    }

        public function testGroupBy()
        {
            $source = $this->provideTestCitiesAsList();
            $expected = $this->provideTestCountriesAsMap();

            $sink = Flow::ofIterable($source)
                ->groupBy(function ($v) {
                    return $v["country"];
                })->collect()->asArray();

            $this->assertSame($expected, $sink);

            $sink = Flow::ofIterable($source)
                ->groupByIndexed(function ($k, $v) {
                    return $v["country"];
                })->collect()->asArray();

            $this->assertSame($expected, $sink);
        }

        public function testSortByAsc()
        {
            $source = $this->provideTestCitiesAsList();

            // asc
            $expected = $this->provideTestCitiesAsListAscending();
            $sink = Flow::ofIterable($source)
                ->sortBy(function ($v1, $v2) {
                    return strcasecmp($v1["city"], $v2["city"]);
                }, false)
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
        }

        public function testSortByDesc()
        {
            $source = $this->provideTestCitiesAsList();

            // desc
            $expected = $this->provideTestCitiesAsListDescending();
            $sink = Flow::ofIterable($source)
                ->sortBy(function ($v1, $v2) {
                    return strcasecmp($v1["city"], $v2["city"]);
                }, true)
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
        }


        public function testTake()
        {
            $source = Flow::ofIterable($this->provideTestCitiesAsList())
                ->map(function ($v) {
                    return $v["city"];
                })
                ->collect()->asArray();

            $sink = Flow::ofIterable($source)
                ->take(0)
                ->collect()->asArray();
            $this->assertSame([], $sink);

            $sink = Flow::ofIterable($source)
                ->take(1)
                ->collect()->asArray();
            $this->assertSame(["Berlin"], $sink);

            $sink = Flow::ofIterable($source)
                ->take(3)
                ->collect()->asArray();
            $this->assertSame(["Berlin", "Hamburg", "London"], $sink);

            $sink = Flow::ofIterable($source)
                ->take(1000)
                ->collect()->asArray();
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
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) {
                    return true;
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) {
                    return true;
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);

            $expected = [];
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) {
                    return false;
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) {
                    return false;
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);


            $pattern = "*D*";
            $expected = [];
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);

            $pattern = "*A*";
            $expected = [
                "a1" => "A1",
                "a2" => "A2",
            ];
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);

            $pattern = "*C*";
            $expected = [];
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);

            $pattern = "*1*";
            $expected = [
                "a1" => "A1",
            ];
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);

            $pattern = "*2*";
            $expected = [];
            $sink = Flow::ofIterable($source)
                ->takeWhile(function ($v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
            $sink = Flow::ofIterable($source)
                ->takeWhileIndexed(function ($k, $v) use ($pattern) {
                    return fnmatch($pattern, $v);
                })
                ->collect()->asArray();
            $this->assertSame($expected, $sink);
        }

            public function testDrop()
            {
                $source = [
                    "a" => "A",
                    "b" => "B",
                    "c" => "C",
                ];

                $sink = Flow::ofIterable($source)
                    ->drop(0)
                    ->collect()->asArray();
                $this->assertSame($source, $sink);

                $sink = Flow::ofIterable($source)
                    ->drop(1)
                    ->collect()->asArray();
                $this->assertSame([
                    "b" => "B",
                    "c" => "C",
                ], $sink);

                $sink = Flow::ofIterable($source)
                    ->drop(2)
                    ->collect()->asArray();
                $this->assertSame([
                    "c" => "C",
                ], $sink);

                $sink = Flow::ofIterable($source)
                    ->drop(1000)
                    ->collect()->asArray();
                $this->assertSame(
                    [], $sink);

                $source = [
                    ["A"],
                    ["B"],
                    ["C"],
                ];
                $sink = Flow::ofIterable($source)
                    ->drop(1)
                    ->collect()->asArray();

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
                $sink = Flow::ofIterable($source)
                    ->dropWhile(function ($v) {
                        return true;
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->dropWhileIndexed(function ($k, $v) {
                        return true;
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);


                $pattern = "*D*";
                $expected = $source;
                $sink = Flow::ofIterable($source)
                    ->dropWhile(function ($v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);


                $pattern = "*A*";
                $expected = [
                    "b1" => "B1",
                    "b2" => "B2",
                    "c1" => "C1",
                    "c2" => "C2",
                ];
                $sink = Flow::ofIterable($source)
                    ->dropWhile(function ($v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);

                $pattern = "*C*";
                $expected = $source;
                $sink = Flow::ofIterable($source)
                    ->dropWhile(function ($v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);

                $pattern = "*1*";
                $expected = [
                    "a2" => "A2",
                    "b1" => "B1",
                    "b2" => "B2",
                    "c1" => "C1",
                    "c2" => "C2",
                ];
                $sink = Flow::ofIterable($source)
                    ->dropWhile(function ($v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);

                $pattern = "*2*";
                $expected = $source;
                $sink = Flow::ofIterable($source)
                    ->dropWhile(function ($v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->dropWhileIndexed(function ($k, $v) use ($pattern) {
                        return fnmatch($pattern, $v);
                    })
                    ->collect()->asArray();
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
                $sink = Flow::ofIterable($source)
                    ->reducing([], function ($acc, $v) { return $v;})
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->reducingIndexed([], function ($acc, $k, $v) { return $v;})
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);

                $expected=['A1A2B1B2C1C2'];
                $sink = Flow::ofIterable($source)
                    ->reducing("", function (string $acc, string $v) { return $acc.$v;})
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
                $sink = Flow::ofIterable($source)
                    ->reducingIndexed("", function (string $acc, $k, string $v) { return $acc.$v;})
                    ->collect()->asArray();
                $this->assertSame($expected, $sink);
            }
}