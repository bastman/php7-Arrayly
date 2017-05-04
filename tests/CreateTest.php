<?php
use function Arrayly\ofArray as s;

class CreateTestCase extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $arrayly = s(["foo" => "bar"]);
        $this->assertInstanceOf('Arrayly\Arrayly', $arrayly);
        $this->assertEquals('bar', (string)$arrayly->getOrNull('foo'));
    }
}