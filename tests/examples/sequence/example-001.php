<?php
declare(strict_types=1);

namespace Arrayly\Test\Examples;

ini_set("display_errors", '1');
require_once __DIR__ . "/../../../vendor/autoload.php";

use Arrayly\Sequence\Sequence as Seq;
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
            //->onEach(function ($v){ echo "BEF.".json_encode($v).PHP_EOL;}
            ->groupBy(function ($v) {
                return $v['country'];
            })
            ->flatMap(function ($itemGroup) {
                return $itemGroup;
            })
            // ->map(function ($v){ echo "map: ".json_encode($v).PHP_EOL;
            //return $v["city"];  })
            //->onEach(function ($v){ echo "AFT.".json_encode($v).PHP_EOL;;})
            ->filter(function ($v) {
                echo "filter: " . json_encode($v) . PHP_EOL;
                return true;
            })
            /*
            ->map(function($v){return $v["city"];})
            ->pipeTo(function(iterable $iterable){

                foreach ($iterable as $k=>$v) {
                    yield $v=>$k;
                }
                //return yield from $iterable;
            })
            */


            ->toArray();

        TestUtils::printTestResult("GENERATORS", $r);
    }

    private static function createCities(): array
    {
        return TestUtils::loadResourceJson('source/cities-list.json');
    }
}

SequenceExamples001::run();