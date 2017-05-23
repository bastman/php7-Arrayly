<?php
declare(strict_types=1);

namespace Arrayly\Test;

use Arrayly\Flow;
use Arrayly\Producers\RewindableProducer;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class FlowRewindableTest extends TestCase
{
    private $debugEnabled = false;

    private function printTestResult($message, $result)
    {
        if ($this->debugEnabled) {
            TestUtils::printTestResult($message, $result);
        }
    }

    private function source(): array
    {
        return $source = [
            "a1" => "a1_Value",
            "a2" => "a2_Value",
            "b1" => "b1_value",
            "b2" => "b2_value",
        ];
    }

    private function sourceGenerator(): \Generator
    {
        $source = $this->source();
        $this->printTestResult("++++++++ generator(): generate source ++++++", $source);

        yield from $source;
    }

    public function testFlowIsReplayable()
    {
        $source = $this->source();
        $sourceSupplier = function () use ($source) {
            $this->printTestResult("++++++++ supplier(): generate source ++++++", $source);

            yield from $this->sourceGenerator();
        };

        $flow = Flow::create()
            ->filter(function ($v) {
                return fnmatch("*b*value*", $v);
            })->map(function ($v) {
                return strtoupper($v);
            });

        $expected = [
            "b1" => "B1_VALUE",
            "b2" => "B2_VALUE",
        ];

        $flowDerived = $flow->withProducerOfIterable($source);
        for ($i = 0; $i < 3; $i++) {
            $r1=$flowDerived->collect()->toArray();
            $r2=$flowDerived->collect()->toArray();
            $sink = $flowDerived->collect()
                ->toArrayly()
                ->toArray();
            $this->assertSame($expected, $sink);
            $this->printTestResult("Flow.withSource(array).collect(): (re-)consume iterator ...", $sink);
        }

        $flowDerived = $flow->withProducerOfIteratorSupplier($sourceSupplier);
        for ($i = 0; $i < 3; $i++) {
            $r1=$flowDerived->collect()->toArray();
            $r2=$flowDerived->collect()->toArray();
            $sink = $flowDerived->collect()
                ->toArrayly()->toArray();
            $this->assertSame($expected, $sink);
            $this->printTestResult("Flow.withSourceSupplier(fn).collect(): (re-)consume iterator ...", $sink);
        }

    }

}