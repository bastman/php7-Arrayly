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
}