<?php

namespace Arrayly\Flow;


use Arrayly\Arrayly;
use Arrayly\Sequence;

final class FlowSink
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
        yield from $this->data;
    }

    public function toSequence(): Sequence
    {
        return Sequence::ofIterable($this->data);
    }

    public function toArrayly(): Arrayly
    {
        return Arrayly::ofIterable($this->data);
    }

}