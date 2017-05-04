<?php
use Arrayly\Arrayly as A;

class ArraylyTestCase extends PHPUnit_Framework_TestCase
{

    public function testConstruct()
    {
        $arrayly = new A(["foo" => "bar"]);
        $this->assertArrayly($arrayly);
        $this->assertEquals('bar', (string)$arrayly->getOrNull('foo'));
    }

    public function assertArrayly($actual)
    {
        $this->assertInstanceOf('Arrayly\Arrayly', $actual);
    }
}