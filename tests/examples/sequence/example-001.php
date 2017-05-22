<?php
declare(strict_types=1);

namespace Arrayly\Test\Examples;

require_once __DIR__."/../../../vendor/autoload.php";

use Arrayly\Sequence as Seq;
use Arrayly\Test\TestUtils;

class SequenceExamples001
{
    public static function run()
    {
        $cities = self::createCities();
        $r = null;
        $r = Seq::ofArray($cities)
            ->keys()
            ->toArray();
        TestUtils::printTestResult("GENERATORS.keys()", $r);

        $r = Seq::ofArray($cities)
            ->onEach(function ($v) {
                echo "peek: start: ".json_encode($v).PHP_EOL;
            })
            ->filter(function (array $v): bool {
                echo "filter: ".json_encode($v).PHP_EOL;

                return $v['country'] === 'Germany';
            })
            ->map(function (array $v): array {
                echo "map: ".json_encode($v).PHP_EOL;

                return $v;
            })
            ->onEach(function ($v) {
                echo "peek: mapped:".json_encode($v).PHP_EOL;
            })
            ->groupBy(function (array $v): string {
                echo "groupBy:".json_encode($v).PHP_EOL;

                return $v['country'];
            })
            ->onEach(function ($v) {
                echo "peek: grouped:".json_encode($v).PHP_EOL;
            })
            ->flatMap(function (array $itemGroup): array {
                echo "flatMap:".json_encode($itemGroup).PHP_EOL;

                return $itemGroup;
            })
            ->onEach(function ($v) {
                echo "peek: flatMapped:".json_encode($v).PHP_EOL;
            })
            ->pipeTo(function (iterable $iterable) {
                foreach ($iterable as $k => $v) {
                    yield $k => $v;
                }
            })
            ->onEach(function ($v) {
                echo "peek: piped:".json_encode($v).PHP_EOL;
            })
            ->toArray();

        TestUtils::printTestResult("GENERATORS", $r);
    }

    private static function createCities(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }
}

SequenceExamples001::run();