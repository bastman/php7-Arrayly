<?php
declare(strict_types=1);

namespace Arrayly\Test\Examples;

ini_set("display_errors", '1');
require_once __DIR__."/../../../vendor/autoload.php";

use Arrayly\Pipeline\Pipeline as Pipe;
use Arrayly\Test\TestUtils;

class PipeExamples001
{
    public static function run()
    {
        TestUtils::printTestResult("pipe start ...", null);

        $cities = self::createCities();

        $p = new Pipe();


        $p->map(function ($v) {
            return $v['country'];
        })//->filter(function($v){ return $v=="Germany";})
        ;

        $sourceSupplier = function () use ($cities) {
            yield from $cities;
        };

        $r = $p->collect($sourceSupplier());

        TestUtils::printTestResult("xPIPELINE", TestUtils::iterableAsArray($r));
        $r = $p->collect($sourceSupplier());
        TestUtils::printTestResult("xPIPELINE", iterator_to_array($r));

        $p2 = new Pipe();
        $p2->map(function ($v) {
            return $v['city'];
        })->reduce("", function ($acc, $v) {
            return $acc."-".$v;
        })
            ->map(function ($v) {
                return strtoupper($v);
            })
            ->filter(function ($v) {
                return true;
            });

        $r = $p2->collectAsSequence($sourceSupplier())
            ->toArrayly()
            ->firstOrNull();
        TestUtils::printTestResult("PIPELINE 2", $r);
        $r = $p2->collectAsSequence($sourceSupplier())
            ->toArrayly()
            ->firstOrNull();
        var_dump($r);
        TestUtils::printTestResult("PIPELINE 2.2", $r);

        TestUtils::printTestResult("PIPELINE 2.3...", null);
        $r = $p2->execute($sourceSupplier());
        var_dump($r);
        foreach ($r as $k => $v) {
            var_dump($v);
        }
        TestUtils::printTestResult("PIPELINE 2.3.done", null);

    }

    private static function createCities(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }

}

PipeExamples001::run();