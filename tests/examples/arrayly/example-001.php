<?php
declare(strict_types=1);

namespace Arrayly\Test\Examples;

require_once __DIR__."/../../../vendor/autoload.php";

use Arrayly\Arrayly as A;
use Arrayly\Test\TestUtils;

class ArraylyExamples001
{
    public static function run()
    {
        $cities = self::createCities();
        TestUtils::printTestResult("source: cities", $cities);

        // take(2)
        $r = A::ofArray($cities)
            ->take(2)
            ->toArray();
        TestUtils::printTestResult("take(2)", $r);

        // drop(2)
        $r = A::ofArray($cities)
            ->drop(2)
            ->toArray();
        TestUtils::printTestResult("drop(2)", $r);

        // map & filter
        $r = A::ofArray($cities)
            ->map(function ($item) {
                return $item["country"];
            })
            ->filter(function ($country) {
                return $country == 'Germany';
            })
            ->toArray();
        TestUtils::printTestResult("map & filter", $r);

        // group by
        $groupedByCountry = A::ofArray($cities)
            ->groupBy(function ($item) {
                return $item["country"];
            })
            ->toArray();
        TestUtils::printTestResult("group by country", $groupedByCountry);

        // flatmap
        $r = A::ofArray($groupedByCountry)
            ->flatMap(function ($itemGroup) {
                return $itemGroup;
            })
            ->toArray();
        TestUtils::printTestResult("countries flatmap to list", $r);

        // reduce
        $r = A::ofArray($cities)
            ->reduce("", function ($acc, $item) {
                return $acc.':'.$item["city"];
            });
        TestUtils::printTestResult("reduce", $r);

        // map & sort (ASC)
        $r = A::ofArray($cities)
            ->map(function ($item) {
                return $item["city"];
            })
            ->sortBy(function ($a, $b) {
                return strcasecmp($a, $b);
            }, false)
            ->toArray();
        TestUtils::printTestResult("map & sort (ASC)", $r);

        // map & sort (DESC)
        $r = A::ofArray($cities)
            ->map(function ($item) {
                return $item["city"];
            })
            ->sortBy(function ($a, $b) {
                return strcasecmp($a, $b);
            }, true)
            ->toArray();
        TestUtils::printTestResult("map & sort (DESC)", $r);
    }

    private static function createCities(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }
}

ArraylyExamples001::run();