<?php
declare(strict_types=1);

namespace Arrayly\Test;

use PHPUnit\Framework\TestCase;
use function Arrayly\ofArray as A;

class FactoryTest extends TestCase
{
    public function testCreate()
    {
        $arrayly = A(["foo" => "bar"]);
        $this->assertInstanceOf('Arrayly\Arrayly', $arrayly);
        $this->assertEquals('bar', (string)$arrayly->getOrNull('foo'));
    }
}