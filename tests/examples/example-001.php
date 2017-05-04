<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Arrayly\Arrayly as A;


class ArraylyExamples001
{

    public static function run()
    {
        $cities = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
            ["city" => "London", "country" => "England"],
            ["city" => "Manchester", "country" => "England"],
            ["city" => "Paris", "country" => "France"],
        ];
        self::printTestResult("source: cities", $cities);

        // take(2)
        $r = A::ofArray($cities)
            ->take(2)
            ->toArray();
        self::printTestResult("take(2)", $r);

        // drop(2)
        $r = A::ofArray($cities)
            ->drop(2)
            ->toArray();
        self::printTestResult("drop(2)", $r);

        // map & filter
        $r = A::ofArray($cities)
            ->map(function ($item) {
                return $item["country"];
            })
            ->filter(function ($country) {
                return $country == 'Germany';
            })
            ->toArray();
        self::printTestResult("map & filter", $r);

        // group by
        $groupedByCountry = A::ofArray($cities)
            ->groupBy(function ($item) {
                return $item["country"];
            })
            ->toArray();
        self::printTestResult("group by country", $groupedByCountry);

        // flatmap
        $r = A::ofArray($groupedByCountry)
            ->flatMap(function ($itemGroup) {
                return $itemGroup;
            })
            ->toArray();
        self::printTestResult("countries flatmap to list", $r);

        // reduce
        $r = A::ofArray($cities)
            ->reduce("", function ($acc, $item) {
                return $acc . ':' . $item["city"];
            });
        self::printTestResult("reduce", $r);

        // map & sort (ASC)
        $r = A::ofArray($cities)
            ->map(function ($item) {
                return $item["city"];
            })
            ->sortBy(function ($a, $b) {
                return strcasecmp($a, $b);
            }, false)
            ->toArray();
        self::printTestResult("map & sort (ASC)", $r);

        // map & sort (DESC)
        $r = A::ofArray($cities)
            ->map(function ($item) {
                return $item["city"];
            })
            ->sortBy(function ($a, $b) {
                return strcasecmp($a, $b);
            }, true)
            ->toArray();
        self::printTestResult("map & sort (DESC)", $r);
    }

    private static function printTestResult($message, $result)
    {
        echo PHP_EOL . "===" . $message . "===" . PHP_EOL;
        echo json_encode($result) . PHP_EOL;
    }
}

ArraylyExamples001::run();