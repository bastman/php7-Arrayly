<?php
declare(strict_types=1);

namespace Arrayly\Test;

use Arrayly\ArrayList as A;

use function Arrayly\listOf;
use function Arrayly\listOfIterable;
use function Arrayly\mapOfIterable;
use Arrayly\Test\TestUtils as TestUtils;
use PHPUnit\Framework\TestCase;

class ArrayListTest extends TestCase
{
    public function testConstruct()
    {
        $arrayList = listOf('a', 'b', 'c');
        $this->assertArrayList($arrayList);
        $this->assertSame(["a", "b", "c"], $arrayList->toArray());

        $arrayList = listOfIterable(["a", "b", "c"]);
        $this->assertArrayList($arrayList);
        $this->assertSame(["a", "b", "c"], $arrayList->toArray());

        $arrayList = listOfIterable(["foo" => "bar"]);
        $this->assertArrayList($arrayList);
        $this->assertSame(["bar"], $arrayList->toArray());
    }

    private function assertArrayList($actual)
    {
        $this->assertInstanceOf('Arrayly\ArrayList', $actual);
    }

}