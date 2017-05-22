<?php
/**
 * Created by PhpStorm.
 * User: sebastians
 * Date: 22.05.17
 * Time: 15:03
 */

namespace Arrayly\Flow;


use Arrayly\Arrayly;
use Arrayly\Sequence\Sequence;

class FlowResult
{
    private $data=[];

    public static function ofArray(array $data){
        return new static($data);
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function asArray(): array
    {
        return $this->data;
    }

    public function asGenerator(): \Generator
    {
        yield from $this->data;
    }

    public function asSequence(): Sequence
    {
        return Sequence::ofIterable($this->data);
    }

    public function asArrayly(): Arrayly
    {
        return Arrayly::ofIterable($this->data);
    }




}