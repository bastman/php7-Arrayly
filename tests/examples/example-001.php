<?php
declare(strict_types=1);

ini_set("display_errors", '1');
require_once __DIR__ . "/../../vendor/autoload.php";

use Arrayly\Arrayly as A;
use Arrayly\Sequence\Sequence as Seq;
use Arrayly\Pipeline\Pipeline as Pipe;

class ArraylyExamples001
{
    private static function createCities():array {
        return $cities = [
            ["city" => "Berlin", "country" => "Germany"],
            ["city" => "Hamburg", "country" => "Germany"],
            ["city" => "London", "country" => "England"],
            ["city" => "Manchester", "country" => "England"],
            ["city" => "Paris", "country" => "France"],
        ];
    }

    public static function run()
    {
        self::arraylyExamples();
        self::seqExamples();
        self::pipeExamples();
    }

    private static function arraylyExamples() {
        $cities = self::createCities();
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



    private static function sequenceOfArray(array $source):\Generator {
        yield from $source;
    }

    private static function printTestResult($message, $result)
    {
        echo PHP_EOL . "===" . $message . "===" . PHP_EOL;
        echo json_encode($result) . PHP_EOL;
    }


    private static function seqExamples() {
        $cities=self::createCities();
        $r=null;
        $r=Seq::ofArray($cities)
            ->keys()

            ->toArray();
        self::printTestResult("GENERATORS.keys()", $r);

        $r=Seq::ofArray($cities)
            //->onEach(function ($v){ echo "BEF.".json_encode($v).PHP_EOL;}
            ->groupBy(function($v){
                return $v['country'];
            })
            ->flatMap(function($itemGroup){ return $itemGroup;  })
            // ->map(function ($v){ echo "map: ".json_encode($v).PHP_EOL;
            //return $v["city"];  })
            //->onEach(function ($v){ echo "AFT.".json_encode($v).PHP_EOL;;})
            ->filter(function ($v) {
                echo "filter: ".json_encode($v).PHP_EOL;
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


            ->toArray()

        ;

        self::printTestResult("GENERATORS", $r);
    }

    private static function pipeExamples() {


        self::printTestResult("pipe start ...", null);

        $cities=self::createCities();

        $p=new Pipe();


        $p->map(function($v){
            return $v['country'];
        })//->filter(function($v){ return $v=="Germany";})
        ;

        $sourceSupplier=function () use ($cities){ yield from $cities;};

        //var_dump("DIED!!!!! ".__FILE__.__LINE__);


                $r=$p->collect($sourceSupplier());

                self::printTestResult("xPIPELINE", self::iterableAsArray($r));
        $r=$p->collect($sourceSupplier());
        self::printTestResult("xPIPELINE", iterator_to_array($r));

        $p2=new Pipe();
        $p2->map(function($v){
            return $v['city'];
        })->reduce("" ,function($acc, $v) { return $acc."-".$v;})
            ->map(function($v){return strtoupper($v);})
            ->filter(function($v){return true;})
        ;

        $r=$p2->collectAsSequence($sourceSupplier())
        ->toArrayly()
            ->firstOrNull();
        self::printTestResult("PIPELINE 2", $r);
        $r=$p2->collectAsSequence($sourceSupplier())
        ->toArrayly()
            ->firstOrNull()
        ;
        var_dump($r);
        self::printTestResult("PIPELINE 2.2", $r);

        self::printTestResult("PIPELINE 2.3...",null);
        $r=$p2->execute($sourceSupplier());
        var_dump($r);
        foreach ($r as $k=>$v) {
            var_dump($v);
        }
        self::printTestResult("PIPELINE 2.3.done",null);

        /*


                $r=$p->collect($source);
                self::printTestResult("xPIPELINE", iterator_to_array($r));

                $p2=new Pipe();
                $p2->map(function($v){
                    return $v['city'];
                })//->reduce("" ,function($acc, $v) { return $acc."-".$v;})
                ;

                $source=yield from $cities;

                $r=$p2->collect($source);
                var_dump($r);
                self::printTestResult("PIPELINE 2", iterator_to_array($r));

                $r=$p2->collect($source);
                self::printTestResult("PIPELINE 2", iterator_to_array($r));
                */
    }


    private static function iterableAsArray(iterable $it):array {
        $sink=[];
        foreach ($it as $k=>$v) {$sink[$k]=$v;}
        return $sink;
    }

}

ArraylyExamples001::run();