<?php
declare(strict_types=1);
namespace Arrayly\Test;
use Arrayly\Arrayly;
use Arrayly\Pipeline\IterableProducer;
use Arrayly\Pipeline\Pipeline as P;
use Arrayly\Pipeline\Pipeline;
use Arrayly\Pipeline\Pipeline2;
use Arrayly\Pipeline\RewindableIterator;
use Arrayly\Test\TestUtils as TestUtils;

use PHPUnit\Framework\TestCase;


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

        $p2=Pipeline2::ofIterable($this->iterableToGenerator($source));
        $p2->filter(function($v){
            return fnmatch("*B*", $v);
        })->map(function ($v) { return strtolower($v);});

        $r=$p2->collectAsArrayly()->toArray();
        var_dump($r);
        $r=$p2->withSource($this->iterableToGenerator($source))
            ->collectAsArrayly()
            ->toArray();
        var_dump($r);


        $p2=Pipeline2::ofIterable($source);
        $p2->filter(function($v){
            return fnmatch("*B*", $v);
        })->map(function ($v) { return strtolower($v);});

        $r=$p2->collectAsArrayly()->toArray();
        var_dump($r);
        $r=$p2->withSource($this->iterableToGenerator($source))
            ->collectAsArrayly()
            ->toArray();
        var_dump($r);




        $p2=Pipeline2::create();
        $p2->filter(function($v){
            return fnmatch("*B*", $v);
        })->map(function ($v) { return strtolower($v);});
        $r=$p2->collectAsArrayly()->toArray();
        var_dump($r);

        $r=$p2->withSource($this->iterableToGenerator($source))
            ->collectAsArrayly()
            ->toArray();
        var_dump($r);

        $r=$p2->withSource($this->iterableToGenerator($source))
            ->collectAsArrayly()
            ->toArray();
        var_dump($r);

        // rewindable tests

        $r=$p2
            ->withSource($source);
        //var_dump($p2);


        $it=RewindableIterator::ofIterable($source);
        var_dump(iterator_to_array($it));
        $it = $it->new();
        var_dump(iterator_to_array($it));


        $p3=$p2->withSource($source);
        $p3=$p2->withSource(RewindableIterator::ofIterable($source));
        //$p3=$p2->withSource($this->iterableToGenerator($source));

        $r=$p3
            ->collectAsArrayly()
            ->toArray();
        var_dump($r);


        $r=$p3
            ->collectAsArrayly()
            ->toArray();
        var_dump($r);


        //var_dump($p2);

        /*
        $it=RewindableIterator::ofIterable($source);
        foreach ($it as $k=>$v){
            var_dump($k);
        }
        var_dump(iterator_to_array($it));
        var_dump(iterator_to_array($it));
        */

        var_dump("++++++done");

        ;
    }

    private function iterableToGenerator(iterable $iterable):\Generator {
        yield from $iterable;
    }

}