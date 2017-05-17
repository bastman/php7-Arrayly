<?php
namespace Arrayly\Test;
use function Arrayly\ofArray as A;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testCreate()
    {
        $arrayly = A(["foo" => "bar"]);
        $this->assertInstanceOf('Arrayly\Arrayly', $arrayly);
        $this->assertEquals('bar', (string)$arrayly->getOrNull('foo'));
    }
}