<?php
declare(strict_types=1);
namespace Arrayly\Test;
use Arrayly\Pipeline\Pipeline as P;
use Arrayly\Pipeline\Pipeline;
use Arrayly\Pipeline\Pipeline2;
use Arrayly\Test\TestUtils as TestUtils;
use function MongoDB\BSON\fromJSON;
use PHPUnit\Framework\TestCase;

/*
ini_set("display_errors", "1");
    $t=new PipelineTest();

    $t->testFoo();
*/
class PipelineTest extends TestCase
{
    public function testFoo() {


        //self::assertTrue(false);
        $source = [
            "a1"=>"A1",
            "a2"=>"A2",
            "b1"=>"B1",
            "b2"=>"B2",
        ];
      //  $sourceGenerator=yield from $source;
        $sourceGeneratorSupplier = function() use ($source) {
            var_dump("++++++++ generate ++++++");
            yield from $source;
        };


        $p=new Pipeline();

        $p->filter(function($v){
            return fnmatch("*B*", $v);
        })->map(function ($v) { return strtolower($v);})

        ;


        $r=$p->collectAsArray($sourceGeneratorSupplier());
        var_dump($r);
        $r=$p->collectAsArray($sourceGeneratorSupplier());
        var_dump($r);

        var_dump("================");

        $p2=new Pipeline2($this->iterableToGenerator($source));

        $p2->filter(function($v){
            return fnmatch("*B*", $v);
        })->map(function ($v) { return strtolower($v);});

        $r=$p2->collectAsSequence()->toArray();
        var_dump($r);

        //$r=$p2->collectAsSequence()->toArray();
        //var_dump($r);


        $gen = $this->iterableToGenerator($source);

        var_dump("++++++done");

        ;
    }

    private function iterableToGenerator(iterable $iterable):\Generator {
        yield from $iterable;
    }

}