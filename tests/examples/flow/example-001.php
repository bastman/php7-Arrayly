<?php
declare(strict_types=1);

namespace Arrayly\Test\Examples;

require_once __DIR__."/../../../vendor/autoload.php";

use Arrayly\Flow;
use Arrayly\Producers\RewindableProducer;
use Arrayly\Test\TestUtils;

class FlowExamples001
{
    public static function run()
    {

        // define the flow
        $flow = Flow::create()
            ->filter(function (array $v): bool {
                return $v['country'] === 'Germany';
            })
            ->map(function (array $v): array {
                return $v;
            })
            ->groupBy(function (array $v): string {
                return $v['country'];
            })
            ->flatMap(function (array $itemGroup): array {
                return $itemGroup;
            });

        // run the flow with a given source
        $cities = self::createCities();
        $sink = $flow->withProducerOfIterable($cities)
            ->collect()
            ->asArray();
        TestUtils::printTestResult("flow.withSource(array) -> results ...", $sink);

        // run the same flow again, but with a different source (supplier)
        $citiesSupplier = function () {
            $source = self::createCities();
            TestUtils::printTestResult("++++++++ supplier(): generate source ++++++", $source);

            yield from $source;
        };

        $flowWithSource = $flow->withProducer(RewindableProducer::ofIteratorSupplier($citiesSupplier));
        $sink = $flowWithSource
            ->collect()
            ->asArray();
        TestUtils::printTestResult("flow.withSourceSupplier(fn) -> results ...", $sink);
        // and re-run it - with the same source supplier
        $sink = $flowWithSource
            ->collect()
            ->asArray();
        TestUtils::printTestResult("flow.withSourceSupplier(fn) rerun -> results ...", $sink);

    }

    private static function createCities(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }
}

FlowExamples001::run();