<?php
declare(strict_types=1);

namespace Arrayly\Flow;


use Arrayly\Arrayly;
use Arrayly\Sequence;

final class FlowSink implements \IteratorAggregate
{
    private $data=[];

    public static function ofArray(array $data){
        return new static($data);
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toGenerator(): \Generator
    {
        return $this->getIterator();
    }

    public function toSequence(): Sequence
    {
        return Sequence::ofIterable($this->data);
    }

    public function toArrayly(): Arrayly
    {
        return Arrayly::ofIterable($this->data);
    }

    public function getIterator(): \Generator
    {
        yield from $this->data;
    }

    public function toIteratorSupplier():\Closure {
        $fn = function () {
            return $this->getIterator();
        };

        return $fn;
    }

}