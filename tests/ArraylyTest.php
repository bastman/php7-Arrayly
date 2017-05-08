<?php
use Arrayly\Arrayly as A;

class ArraylyTestCase extends PHPUnit_Framework_TestCase
{

    private function provideTestCities(): array
    {
        return $cities = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
            ["city" => "London", "country" => "England"],
            ["city" => "Manchester", "country" => "England"],
            ["city" => "Paris", "country" => "France"],
        ];
    }

    public function testConstruct()
    {
        $arrayly = new A(["foo" => "bar"]);
        $this->assertArrayly($arrayly);
        $this->assertEquals('bar', (string)$arrayly->getOrNull('foo'));
    }

    public function assertArrayly($actual)
    {
        $this->assertInstanceOf('Arrayly\Arrayly', $actual);
    }

    public function testMap()
    {
        $source = $this->provideTestCities();

        $sink = A::ofArray($source)
            ->map(function ($v) {
                return $v["city"];
            })->toArray();

        $this->assertSame(["Berlin", "Hamburg", "London", "Manchester", "Paris"], $sink);
    }

    public function testReduce()
    {
        $source = $this->provideTestCities();

        $sink = A::ofArray($source)
            ->map(function ($v) {
                return $v["city"];
            })
            ->reduce('', function ($acc, $v){ return $acc.'-'.$v;})
            ;
        $this->assertSame("-Berlin-Hamburg-London-Manchester-Paris", $sink);
    }

    public function testFilter()
    {
        $source = $this->provideTestCities();

        $sink = A::ofArray($source)
            ->filter(function ($v) {
                return $v["country"] === "Germany";
            })->toArray();

        $this->assertSame([["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"]], $sink);
    }

    public function testTake()
    {
        $source = A::ofArray($this->provideTestCities())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = A::ofArray($source)
            ->take(0)
            ->toArray();
        $this->assertSame([], $sink);

        $sink = A::ofArray($source)
            ->take(1)
            ->toArray();
        $this->assertSame(["Berlin"], $sink);

        $sink = A::ofArray($source)
            ->take(3)
            ->toArray();
        $this->assertSame(["Berlin","Hamburg","London"], $sink);

        $sink = A::ofArray($source)
            ->take(1000)
            ->toArray();
        $this->assertSame(["Berlin","Hamburg","London","Manchester", "Paris"], $sink);
    }

    public function testDropValues()
    {
        $source = A::ofArray($this->provideTestCities())
            ->map(function ($v) {
                return $v["city"];
            })
            ->toArray();

        $sink = A::ofArray($source)
            ->drop(0)
            ->values()
            ->toArray();
        $this->assertSame(["Berlin","Hamburg","London","Manchester", "Paris"], $sink);

        $sink = A::ofArray($source)
            ->drop(1)
            ->values()
            ->toArray();
        $this->assertSame(["Hamburg","London","Manchester", "Paris"], $sink);

        $sink = A::ofArray($source)
            ->drop(3)
            ->values()
            ->toArray();
        $this->assertSame(["Manchester", "Paris"], $sink);

        $sink = A::ofArray($source)
            ->drop(1000)
            ->values()
            ->toArray();
        $this->assertSame([], $sink);
    }

    public function testDrop()
    {
        $source = [
            "a"=>"A",
            "b"=>"B",
            "c"=>"C"
        ];

        $sink = A::ofArray($source)
            ->drop(0)
            ->toArray();
        $this->assertSame($source, $sink);

        $sink = A::ofArray($source)
            ->drop(1)
            ->toArray();
        $this->assertSame([
            "b"=>"B",
            "c"=>"C"
        ], $sink);

        $sink = A::ofArray($source)
            ->drop(2)
            ->toArray();
        $this->assertSame([
            "c"=>"C"
        ], $sink);

        $sink = A::ofArray($source)
            ->drop(1000)
            ->toArray();
        $this->assertSame(
            [], $sink);

        $source = [
            ["A"],
            ["B"],
            ["C"]
        ];
        $sink = A::ofArray($source)
            ->drop(1)
            ->toArray();

        $this->assertSame(
            [
                "1"=>["B"],
                "2"=>["C"],
            ], $sink);
    }


    public function testGroupBy()
    {
        $source = $this->provideTestCities();

        $sink = A::ofArray($source)
            ->groupBy(function ($v) {
                return $v["country"];
            })->toArray();

        $expected = [
            "Germany" => [
                [
                    "city" => "Berlin",
                    "country" => "Germany"
                ],
                [
                    "city" => "Hamburg",
                    "country" => "Germany"
                ]
            ],
            "England" => [
                [
                    "city" => "London",
                    "country" => "England"
                ],
                [
                    "city" => "Manchester",
                    "country" => "England"
                ]
            ],
            "France" => [
                [
                    "city" => "Paris",
                    "country" => "France"
                ]
            ]
        ];

        $this->assertSame($expected, $sink);
    }

    public function testFlatMap()
    {
        $source = [
            "Germany" => [
                [
                    "city" => "Berlin",
                    "country" => "Germany"
                ],
                [
                    "city" => "Hamburg",
                    "country" => "Germany"
                ]
            ],
            "England" => [
                [
                    "city" => "London",
                    "country" => "England"
                ],
                [
                    "city" => "Manchester",
                    "country" => "England"
                ]
            ],
            "France" => [
                [
                    "city" => "Paris",
                    "country" => "France"
                ]
            ]
        ];

        $sink = A::ofArray($source)
            ->flatMap(function ($v) {
                return $v;
            })->toArray();

        $expected = $this->provideTestCities();

        $this->assertSame($expected, $sink);
    }
}